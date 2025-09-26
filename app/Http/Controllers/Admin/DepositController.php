<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\DepositRejected;
use App\Mail\DepositConfirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class DepositController extends Controller
{
    public function index()
    {
        // Ambil transaksi dengan type 'deposit' saja
        $deposits = Transaction::with('user')
            ->where('type', 'deposit')
            ->latest('created_at')
            ->get();

        return view('admin.pages.deposit.index', compact('deposits'));
    }

    public function edit($id)
    {
        $deposit = Transaction::with('user')
            ->where('type', 'deposit')
            ->findOrFail($id);

        return view('admin.pages.deposit.detail', compact('deposit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // Cari user berdasarkan nomor HP
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User dengan nomor HP tersebut tidak ditemukan.');
        }

        // Generate reference number
        $reference = 'DEP-' . strtoupper(uniqid());

        // Buat deposit baru dengan status success (auto confirmed)
        Transaction::create([
            'user_id' => $user->id,
            'product_id' => null, // Null untuk deposit
            'reference' => $reference,
            'amount' => $request->amount,
            'type' => 'deposit',
            'status' => 'success', // Auto confirmed untuk manual deposit
            'payment_method' => $request->method,
            'payment_proof' => null, // Tidak ada bukti karena dibuat manual oleh admin
            'approved_by' => auth()->id(), // ID admin yang membuat
        ]);

        return redirect()->back()->with('success', 'Deposit berhasil ditambahkan untuk user ' . $user->name);
    }

    public function confirm($id)
    {
        $deposit = Transaction::where('type', 'deposit')->findOrFail($id);

        if (!in_array($deposit->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Deposit tidak dapat dikonfirmasi.');
        }

        $deposit->update([
            'status' => 'success',
            'approved_by' => auth()->id(),
        ]);

        Log::info('Deposit confirmed', [
            'deposit_id' => $deposit->id,
            'user_id' => $deposit->user_id,
            'amount' => $deposit->amount
        ]);

        // Send confirmation email
        try {
            Mail::to($deposit->user->email)->send(new DepositConfirmed($deposit));
            Log::info('Deposit confirmation email sent', [
                'deposit_id' => $deposit->id,
                'user_email' => $deposit->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit confirmation email', [
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage()
            ]);
        }

        $referralUsage = DB::table('referral_usages')
            ->where('used_by', $deposit->user_id)
            ->first();
        Log::info('Referral usage check', [
            'user_id' => $deposit->user_id,
            'referral_usage_found' => $referralUsage ? 'yes' : 'no',
            'referral_usage' => $referralUsage
        ]);

        if ($referralUsage) {
            $successfulDepositCount = Transaction::where('user_id', $deposit->user_id)
                ->where('type', 'deposit')
                ->where('status', 'success')
                ->where('id', '!=', $deposit->id)
                ->count();
            Log::info('Successful deposit count', [
                'user_id' => $deposit->user_id,
                'count' => $successfulDepositCount,
                'is_first_deposit' => $successfulDepositCount == 0 ? 'yes' : 'no'
            ]);
            if ($successfulDepositCount == 0) {
                $commissionRate = DB::table('configs')
                    ->where('key', 'team_invite_presentation')
                    ->value('value');
                Log::info('Commission rate check', [
                    'raw_commission_rate' => $commissionRate,
                    'commission_rate_type' => gettype($commissionRate)
                ]);

                if ($commissionRate) {
                    $commissionRate = json_decode($commissionRate, true) ?? $commissionRate;
                    $commissionRate = (float) $commissionRate;

                    Log::info('Commission rate converted', [
                        'converted_rate' => $commissionRate,
                        'is_valid_percentage' => ($commissionRate > 0 && $commissionRate <= 100) ? 'yes' : 'no'
                    ]);

                    if ($commissionRate > 0 && $commissionRate <= 100) {
                        $commissionAmount = $deposit->amount * ($commissionRate / 100);
                        Log::info('Commission calculation', [
                            'deposit_amount' => $deposit->amount,
                            'commission_rate' => $commissionRate,
                            'commission_amount' => $commissionAmount,
                            'referral_owner_id' => $referralUsage->user_referral
                        ]);

                        try {
                            $commissionTransaction = Transaction::create([
                                'user_id' => $referralUsage->user_referral,
                                'wallet_id' => null,
                                'product_id' => null,
                                'reference' => 'COM-' . time() . '-' . $deposit->user_id,
                                'amount' => $commissionAmount,
                                'type' => 'commission',
                                'withdrawal_fee' => 0,
                                'status' => 'success',
                                'payment_method' => 'referral_commission',
                                'payment_proof' => null,
                            ]);

                            Log::info('Commission transaction created successfully', [
                                'commission_transaction_id' => $commissionTransaction->id,
                                'amount' => $commissionAmount,
                                'for_user_id' => $referralUsage->user_referral
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to create commission transaction', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                        }
                    } else {
                        Log::warning('Invalid commission rate', [
                            'commission_rate' => $commissionRate
                        ]);
                    }
                } else {
                    Log::warning('No commission rate found in config');
                }
            } else {
                Log::info('Not first deposit, skipping commission', [
                    'user_id' => $deposit->user_id,
                    'previous_successful_deposits' => $successfulDepositCount
                ]);
            }
        } else {
            Log::info('No referral usage found for user', [
                'user_id' => $deposit->user_id
            ]);
        }

        return redirect()->back()->with('success', 'Deposit berhasil dikonfirmasi.');
    }

    public function reject(Request $request, $id)
    {
        $deposit = Transaction::where('type', 'deposit')->findOrFail($id);

        $deposit->update([
            'status' => 'failed',
            'approved_by' => auth()->id(),
        ]);

        // Send rejection email
        try {
            Mail::to($deposit->user->email)->send(new DepositRejected($deposit));
            Log::info('Deposit rejection email sent', [
                'deposit_id' => $deposit->id,
                'user_email' => $deposit->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit rejection email', [
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Deposit berhasil ditolak.');
    }
}
