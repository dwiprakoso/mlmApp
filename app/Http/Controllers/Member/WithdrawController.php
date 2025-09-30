<?php

namespace App\Http\Controllers\Member;

use App\Models\Config;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\WithdrawalNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function index()
    {
        $wallets = Auth::user()->wallets()->orderByDesc('is_primary')->orderBy('created_at')->get();

        // ✅ NEW: Get withdrawable balance (revenue + commission + deposit)
        $withdrawableBalance = Transaction::getWithdrawableBalance(Auth::id());

        // ✅ NEW: Get balance breakdown for display (optional)
        $balanceBreakdown = Transaction::getBalanceBreakdown(Auth::id());

        $withdrawalFeeConfig = Config::where('key', 'withdrawal_fee')->first();
        $withdrawalFeePercent = $withdrawalFeeConfig ? (float) $withdrawalFeeConfig->value : 0;

        return view('member.pages.withdraw.index', compact(
            'wallets',
            'withdrawableBalance',
            'balanceBreakdown',
            'withdrawalFeePercent'
        ));
    }

    public function store(Request $request)
    {
        Log::info('Withdrawal request:', $request->all());

        $validator = Validator::make($request->all(), [
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:50000|max:50000000',
            'notes' => 'nullable|string|max:255'
        ], [
            'wallet_id.required' => 'Pilih wallet untuk penarikan',
            'wallet_id.exists' => 'Wallet tidak valid',
            'amount.required' => 'Jumlah penarikan harus diisi',
            'amount.numeric' => 'Jumlah penarikan harus berupa angka',
            'amount.min' => 'Minimal penarikan IDR 50,000',
            'amount.max' => 'Maksimal penarikan IDR 50,000,000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $wallet = Auth::user()->wallets()->find($request->wallet_id);
        if (!$wallet) {
            return back()->with('error', 'Wallet tidak valid')->withInput();
        }

        $amount = $request->amount;

        $withdrawalFeeConfig = Config::where('key', 'withdrawal_fee')->first();
        $withdrawalFeePercent = $withdrawalFeeConfig ? (float) $withdrawalFeeConfig->value : 0;

        // Calculate withdrawal fee
        $withdrawalFee = ($amount * $withdrawalFeePercent) / 100;
        $withdrawalFee = round($withdrawalFee);
        $netAmount = $amount - $withdrawalFee;

        // ✅ NEW: Check withdrawable balance
        $withdrawValidation = Transaction::canWithdraw(Auth::id(), $amount);

        if (!$withdrawValidation['can_withdraw']) {
            $balanceBreakdown = Transaction::getBalanceBreakdown(Auth::id());

            $errorMessage = 'Saldo tidak mencukupi untuk penarikan. ' .
                'Saldo tersedia: IDR ' . number_format($withdrawValidation['available'], 0, ',', '.') . ', ' .
                'Jumlah penarikan: IDR ' . number_format($amount, 0, ',', '.') . '. ' .
                '(Deposit: IDR ' . number_format($balanceBreakdown['deposit'], 0, ',', '.') . ', ' .
                'Revenue: IDR ' . number_format($balanceBreakdown['revenue'], 0, ',', '.') . ', ' .
                'Commission: IDR ' . number_format($balanceBreakdown['commission'], 0, ',', '.') . ')';

            return back()->with('error', $errorMessage)->withInput();
        }

        // ✅ NEW: Calculate withdrawal allocation (priority: revenue → commission → deposit)
        $allocation = Transaction::calculateWithdrawalAllocation(Auth::id(), $amount);

        if (!$allocation['is_sufficient']) {
            return back()->with('error', 'Terjadi kesalahan dalam menghitung alokasi penarikan')->withInput();
        }

        try {
            DB::beginTransaction();

            // ✅ FIX: Generate main reference untuk grouping
            $mainReference = $this->generateWithdrawalReference();
            $createdTransactions = [];

            // ✅ FIX: Create separate transactions dengan UNIQUE reference per transaction
            foreach ($allocation['allocation'] as $sourceType => $sourceAmount) {
                if ($sourceAmount > 0) {
                    // ✅ Generate unique reference untuk setiap transaction
                    $uniqueReference = $mainReference . '-' . strtoupper(substr($sourceType, 0, 3));
                    // Hasil: WD-1727699143-REV, WD-1727699143-COM, WD-1727699143-DEP

                    $transaction = Transaction::create([
                        'user_id' => Auth::id(),
                        'product_id' => null,
                        'reference' => $uniqueReference, // ✅ FIX: Unique per transaction
                        'amount' => $sourceAmount,
                        'withdrawal_fee' => $sourceType === array_key_first(array_filter($allocation['allocation']))
                            ? $withdrawalFee  // Apply fee to first source only
                            : 0,
                        'type' => 'withdraw',
                        'source_balance_type' => $sourceType, // ✅ Track source
                        'wallet_id' => $wallet->id,
                        'status' => 'pending',
                        'payment_method' => null,
                        'payment_proof' => null,
                        'approved_by' => null,
                    ]);

                    $createdTransactions[] = $transaction;

                    Log::info('Withdrawal transaction part created', [
                        'transaction_id' => $transaction->id,
                        'reference' => $uniqueReference, // ✅ Log unique reference
                        'source_type' => $sourceType,
                        'amount' => $sourceAmount,
                        'withdrawal_fee' => $transaction->withdrawal_fee
                    ]);
                }
            }

            if ($request->notes) {
                Log::info('Withdrawal notes:', [
                    'main_reference' => $mainReference, // ✅ Log main reference
                    'notes' => $request->notes
                ]);
            }

            // Send email notification
            try {
                // Get the main transaction (first one) for email
                $mainTransaction = $createdTransactions[0];

                // Create allocation summary for email
                $allocationSummary = [];
                foreach ($allocation['allocation'] as $sourceType => $sourceAmount) {
                    if ($sourceAmount > 0) {
                        $allocationSummary[] = ucfirst($sourceType) . ': IDR ' . number_format($sourceAmount, 0, ',', '.');
                    }
                }

                Mail::to('richkingdomltd@gmail.com')->send(
                    new WithdrawalNotification(
                        $mainTransaction,
                        $withdrawalFee,
                        $netAmount,
                        $allocationSummary // Optional: pass allocation info
                    )
                );
                Log::info('Withdrawal notification email sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send withdrawal notification email: ' . $e->getMessage());
                // Don't fail the transaction if email fails
            }

            DB::commit();

            Log::info('Withdrawal transactions created successfully:', [
                'main_reference' => $mainReference, // ✅ Log main reference
                'total_amount' => $amount,
                'allocation' => $allocation['allocation'],
                'withdrawal_fee' => $withdrawalFee,
                'net_amount' => $netAmount,
                'transactions_count' => count($createdTransactions)
            ]);

            // Create success message with allocation details
            $allocationDetails = [];
            foreach ($allocation['allocation'] as $sourceType => $sourceAmount) {
                if ($sourceAmount > 0) {
                    $allocationDetails[] = ucfirst($sourceType) . ': IDR ' . number_format($sourceAmount, 0, ',', '.');
                }
            }

            $successMessage = "Permintaan penarikan berhasil dibuat. " .
                "ID Transaksi: {$mainReference}. " . // ✅ Show main reference
                "Total: IDR " . number_format($amount, 0, ',', '.') . ". " .
                "Alokasi: " . implode(', ', $allocationDetails) . ". " .
                "Biaya admin: IDR " . number_format($withdrawalFee, 0, ',', '.') . ". " .
                "Jumlah diterima: IDR " . number_format($netAmount, 0, ',', '.');

            return redirect()->route('member.withdraw.log')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating withdrawal transaction:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal membuat permintaan penarikan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ✅ Method generateWithdrawalReference tetap sama
    private function generateWithdrawalReference()
    {
        return 'WD-' . time();
    }

    public function log()
    {
        $withdrawals = Transaction::where('user_id', Auth::id())
            ->where('type', 'withdraw')
            ->with('wallet')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('member.pages.withdraw.log', compact('withdrawals'));
    }
}
