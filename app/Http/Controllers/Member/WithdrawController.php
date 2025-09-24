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
        $wallets = Auth::user()->wallets()->orderByDesc('is_primary')->orderBy('created_at')->get();

        $availableBalance = Transaction::calculateUserBalance(Auth::id());

        return view('member.pages.withdraw.index', compact('wallets', 'availableBalance'));
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

        $availableBalance = Transaction::calculateUserBalance(Auth::id());

        if ($amount > $availableBalance) {
            return back()->with('error', 'Saldo tidak mencukupi')->withInput();
        }

        try {
            DB::beginTransaction();

            $reference = $this->generateWithdrawalReference();

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => null,
                'reference' => $reference,
                'amount' => $amount,
                'type' => 'withdraw',
                'wallet_id' => $wallet->id,
                'status' => 'pending',
                'payment_method' => null,
                'payment_proof' => null,
                'approved_by' => null,
            ]);
            if ($request->notes) {
                Log::info('Withdrawal notes:', ['transaction_id' => $transaction->id, 'notes' => $request->notes]);
            }

            DB::commit();
            Log::info('Withdrawal transaction created successfully:', $transaction->toArray());

            return redirect()->route('member.withdraw.log')
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
        do {
            $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $reference = $prefix . $randomNumber;
        } while (Transaction::where('reference', $reference)->exists());

        return $reference;
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
