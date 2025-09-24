<?php

namespace App\Http\Controllers\Member;

use App\Models\Wallet;
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


        try {
            DB::beginTransaction();

            // Generate unique reference ID untuk withdrawal (WD-6digit)
            $reference = $this->generateWithdrawalReference();

            // Create withdrawal transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => null, // null untuk withdrawal
                'reference' => $reference,
                'amount' => $amount,
                'type' => 'withdraw',
                'wallet_id' => $wallet->id, // ID wallet yang dipilih user
                'status' => 'pending', // Status pending
                'payment_method' => null, // null untuk withdrawal
                'payment_proof' => null, // null untuk withdrawal
                'approved_by' => null, // null karena belum diapprove
            ]);

            // Optional: Simpan notes di field terpisah jika diperlukan
            // Atau bisa menambah field 'notes' di tabel transactions
            if ($request->notes) {
                // Jika ada field notes di tabel transactions, uncomment baris berikut:
                // $transaction->update(['notes' => $request->notes]);

                // Atau simpan di tabel terpisah jika diperlukan
                Log::info('Withdrawal notes:', ['transaction_id' => $transaction->id, 'notes' => $request->notes]);
            }

            // TODO: Implementasi pengurangan saldo user sesuai business logic
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
     * Generate unique withdrawal reference (WD-6digit)
     */
    private function generateWithdrawalReference(): string
    {
        $prefix = 'WD-';

        // Generate 6 digit random number
        do {
            $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $reference = $prefix . $randomNumber;
        } while (Transaction::where('reference', $reference)->exists());

        return $reference;
    }


    public function log()
    {
        // Ambil semua withdrawal transactions user
        $withdrawals = Transaction::where('user_id', Auth::id())
            ->where('type', 'withdraw')
            ->with('wallet')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('member.pages.withdraw.log', compact('withdrawals'));
    }

    /**
     * Get available balance for withdrawal
     */
    private function getAvailableBalance(): float
    {
        $user = Auth::user();

        // Hitung total deposit yang sukses
        $totalDeposit = Transaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->where('status', 'success')
            ->sum('amount');

        // Hitung total withdrawal yang sudah sukses atau pending
        $totalWithdraw = Transaction::where('user_id', $user->id)
            ->where('type', 'withdraw')
            ->whereIn('status', ['success', 'pending'])
            ->sum('amount');

        return $totalDeposit - $totalWithdraw;
    }
}
