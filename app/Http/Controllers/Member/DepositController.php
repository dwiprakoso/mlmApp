<?php

namespace App\Http\Controllers\Member;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\DepositNotification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Config;

class DepositController extends Controller
{
    public function index()
    {
        // Generate form token untuk prevent duplicate submission
        $formToken = bin2hex(random_bytes(16));
        session(['deposit_form_token' => $formToken]);

        return view('member.pages.deposit.index', compact('formToken'));
    }

    public function store(Request $request)
    {
        // Validate form token
        if ($request->form_token !== session('deposit_form_token')) {
            return redirect()->back()
                ->with('error', 'Form submission tidak valid. Silakan coba lagi.')
                ->withInput();
        }

        $request->validate([
            'amount' => 'required|numeric|min:50000|max:50000000',
            'payment_method' => 'required|string'
        ]);

        // Check if user has recent pending deposit (last 2 minutes)
        $recentDeposit = Transaction::where('user_id', Auth::id())
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->where('amount', $request->amount)
            ->where('payment_method', $request->payment_method)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->first();

        if ($recentDeposit) {
            // Remove token to prevent reuse
            session()->forget('deposit_form_token');

            return redirect()->route('member.deposit.payment', $recentDeposit->id)
                ->with('info', 'Deposit ini sudah dibuat sebelumnya dengan kode: ' . $recentDeposit->reference);
        }

        // Use database transaction untuk ensure atomicity
        DB::beginTransaction();
        try {
            // Generate reference dengan format DEP-6 digit random
            $reference = 'DEP-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

            // Pastikan reference unik dengan lock
            $maxAttempts = 5;
            $attempts = 0;
            while (Transaction::where('reference', $reference)->exists() && $attempts < $maxAttempts) {
                $reference = 'DEP-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
                $attempts++;
            }

            if ($attempts >= $maxAttempts) {
                throw new \Exception('Gagal generate reference number yang unik');
            }

            // Buat transaksi deposit
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => null,
                'reference' => $reference,
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_proof' => null,
                'approved_by' => null,
            ]);

            DB::commit();

            // Remove token setelah berhasil
            session()->forget('deposit_form_token');

            // Send email notification (async, jangan block transaction)
            try {
                Mail::to('richkingdomltd@gmail.com')->send(new DepositNotification($transaction));
                Log::info('Deposit notification email sent successfully', ['transaction_id' => $transaction->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send deposit notification email: ' . $e->getMessage(), [
                    'transaction_id' => $transaction->id
                ]);
                // Don't fail the transaction if email fails
            }

            return redirect()->route('member.deposit.payment', $transaction->id)
                ->with('success', 'Deposit berhasil dibuat dengan kode: ' . $reference);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create deposit transaction: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat deposit. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function payment($id)
    {
        $deposit = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'deposit')
            ->firstOrFail();

        // Get dynamic payment methods from configs table
        $configs = Config::whereIn('key', [
            'app_name',
            'app_logo',
            'app_description',
            'bank_name',
            'bank_account_number',
            'account_name',
            'payment_qr_code'
        ])->pluck('value', 'key');

        $paymentMethods = [
            'wallet_qris' => [
                'name' => 'QRIS',
                'accounts' => [
                    [
                        'type' => 'QRIS',
                        'qr_code' => $configs['payment_qr_code'] ?? null,
                        'name' => 'QRIS Payment'
                    ]
                ]
            ],
            'bank_transfer' => [
                'name' => 'Bank Transfer',
                'accounts' => [
                    [
                        'type' => $configs['bank_name'] ?? 'Bank',
                        'number' => $configs['bank_account_number'] ?? '',
                        'name' => $configs['account_name'] ?? ''
                    ]
                ]
            ]
        ];

        return view('member.pages.deposit.payment', compact('deposit', 'paymentMethods'));
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $deposit = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->firstOrFail();

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = 'deposit_proof_' . $deposit->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('deposits/proofs', $fileName, 'public');

            $deposit->update([
                'payment_proof' => $filePath,
                'status' => 'waiting_confirmation'
            ]);
        }

        return redirect()->route('member.deposit.log')
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function log()
    {
        $deposits = Transaction::where('user_id', Auth::id())
            ->where('type', 'deposit')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.pages.deposit.log', compact('deposits'));
    }
}
