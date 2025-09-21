<?php

namespace App\Http\Controllers\Member;

use App\Models\Wallet;
use App\Models\Withdrawal;
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

            $fee = Withdrawal::calculateFee($amount);
            $netAmount = $amount - $fee;

            $withdrawalData = [
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'transaction_id' => Withdrawal::generateTransactionId(),
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'status' => Withdrawal::STATUS_PENDING,
                'payment_method' => $wallet->wallet_type,
                'notes' => $request->notes,
                'requested_at' => now(),
            ];

            // Copy wallet details ke withdrawal record
            if ($wallet->wallet_type === 'bank') {
                $withdrawalData = array_merge($withdrawalData, [
                    'bank_name' => $wallet->bank_name,
                    'bank_account' => $wallet->bank_account,
                    'account_name' => $wallet->account_name,
                ]);
            } else {
                $withdrawalData = array_merge($withdrawalData, [
                    'ewallet_provider' => $wallet->ewallet_provider,
                    'ewallet_number' => $wallet->ewallet_number,
                    'ewallet_name' => $wallet->ewallet_name,
                ]);
            }

            $withdrawal = Withdrawal::create($withdrawalData);

            // TODO: Kurangi saldo user di sini sesuai logic bisnis Anda
            // $this->deductUserBalance(Auth::id(), $amount);

            DB::commit();
            Log::info('Withdrawal created successfully:', $withdrawal->toArray());

            return redirect()->route('member.withdraw.index')
                ->with('success', "Permintaan penarikan berhasil dibuat. ID Transaksi: {$withdrawal->transaction_id}");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating withdrawal:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal membuat permintaan penarikan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function log()
    {
        $withdrawals = Auth::user()->withdrawals()
            ->with('wallet')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('member.pages.withdraw.log', compact('withdrawals'));
    }

    /**
     * Get available balance for withdrawal
     * Sesuaikan dengan logic bisnis Anda
     */
    private function getAvailableBalance(): float
    {
        // TODO: Implement sesuai dengan sistem saldo Anda
        // Misalnya dari tabel user_balances atau transactions
        return 75000; // Contoh saldo
    }

    /**
     * Check daily withdrawal limit
     */
    private function checkDailyLimit(int $userId): bool
    {
        $today = now()->format('Y-m-d');

        $todayWithdrawals = Withdrawal::forUser($userId)
            ->whereDate('requested_at', $today)
            ->whereIn('status', [
                Withdrawal::STATUS_PENDING,
                Withdrawal::STATUS_PROCESSING,
                Withdrawal::STATUS_COMPLETED
            ])
            ->count();

        return $todayWithdrawals >= 1; // Max 1 withdrawal per day
    }

    /**
     * Check if current time is within withdrawal hours (09:00 - 18:00)
     */
    private function isWithdrawalTimeAllowed(): bool
    {
        $currentHour = now()->format('H');
        return $currentHour >= 9 && $currentHour < 18;
    }

    /**
     * Deduct user balance
     * TODO: Implement sesuai dengan sistem saldo Anda
     */
    private function deductUserBalance(int $userId, float $amount): void
    {
        // Implement logic untuk mengurangi saldo user
        // Misalnya update tabel user_balances atau buat record di transactions table
    }
}
