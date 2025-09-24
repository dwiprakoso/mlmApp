<?php

namespace App\Http\Controllers\Member;

use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function index()
    {
        // Ambil wallet user untuk pilihan withdrawal
        $wallets = Auth::user()->wallets()->orderByDesc('is_primary')->orderBy('created_at')->get();

        // Ambil saldo yang bisa ditarik (ini sesuaikan dengan logic bisnis Anda)
        $availableBalance = $this->getAvailableBalance();

        return view('member.pages.withdraw.index', compact('wallets', 'availableBalance'));
    }

    public function store(Request $request)
    {
        Log::info('Withdrawal request:', $request->all());

        $validator = Validator::make($request->all(), [
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:50000|max:50000000', // IDR 50,000 - 50,000,000
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

        // Validasi wallet milik user
        $wallet = Auth::user()->wallets()->find($request->wallet_id);
        if (!$wallet) {
            return back()->with('error', 'Wallet tidak valid')->withInput();
        }

        $amount = $request->amount;
        $availableBalance = $this->getAvailableBalance();

        // Cek saldo mencukupi
        if ($amount > $availableBalance) {
            return back()->with('error', 'Saldo tidak mencukupi')->withInput();
        }

        // Cek limit harian
        if ($this->checkDailyLimit(Auth::id())) {
            return back()->with('error', 'Batas penarikan harian telah tercapai (1 kali per hari)')->withInput();
        }

        // Cek waktu operasional (09:00 - 18:00)
        if (!$this->isWithdrawalTimeAllowed()) {
            return back()->with('error', 'Penarikan hanya diperbolehkan pada jam 09:00 - 18:00')->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate unique reference ID untuk withdrawal
            $reference = $this->generateWithdrawalReference();

            // Hitung fee dan net amount
            $fee = $amount * 0.10; // 10% fee
            $netAmount = $amount - $fee;

            // Create withdrawal transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => null, // Withdrawal tidak memerlukan product
                'reference' => $reference,
                'amount' => $amount,
                'type' => 'withdraw',
                'status' => 'pending', // Default status pending
                'payment_method' => $wallet->wallet_type === 'bank' ? 'bank_transfer' : $wallet->ewallet_provider,
                'payment_proof' => null, // Withdrawal tidak memerlukan payment proof
                'approved_by' => null, // Belum disetujui
            ]);

            // Store withdrawal details in separate table or JSON field
            // Anda bisa membuat tabel withdrawal_details atau menyimpan di JSON
            $this->storeWithdrawalDetails($transaction->id, [
                'wallet_id' => $wallet->id,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'notes' => $request->notes,
                'wallet_details' => $wallet->wallet_type === 'bank' ? [
                    'bank_name' => $wallet->bank_name,
                    'bank_account' => $wallet->bank_account,
                    'account_name' => $wallet->account_name,
                ] : [
                    'ewallet_provider' => $wallet->ewallet_provider,
                    'ewallet_number' => $wallet->ewallet_number,
                    'ewallet_name' => $wallet->ewallet_name,
                ]
            ]);

            // TODO: Kurangi saldo user di sini sesuai logic bisnis Anda
            // $this->deductUserBalance(Auth::id(), $amount);

            DB::commit();
            Log::info('Withdrawal transaction created successfully:', $transaction->toArray());

            return redirect()->route('member.withdraw.index')
                ->with('success', "Permintaan penarikan berhasil dibuat. ID Transaksi: {$transaction->reference}");
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

    /**
     * Generate unique withdrawal reference
     */
    private function generateWithdrawalReference(): string
    {
        $prefix = 'WTH-';
        $timestamp = now()->format('ymd');
        $random = strtoupper(substr(uniqid(), -6));

        return $prefix . $timestamp . $random;
    }


    public function log()
    {
        $withdrawals = Auth::user()->withdrawals()
            ->with('wallet')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('member.pages.withdraw.log', compact('withdrawals'));
    }
    private function getAvailableBalance(): float
    {
        $user = auth()->user();
        $balance = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'success')
            ->sum('amount');

        return $balance;
    }
}
