<?php

namespace App\Http\Controllers\Member;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\DepositNotification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Config; // Add this import

class DepositController extends Controller
{
    public function index()
    {
        return view('member.pages.deposit.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000|max:50000000',
            'payment_method' => 'required|string'
        ]);

        // Generate reference dengan format DEP-6 digit random
        $reference = 'DEP-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Pastikan reference unik
        while (Transaction::where('reference', $reference)->exists()) {
            $reference = 'DEP-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
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

        // Send email notification
        try {
            Mail::to('richkingdomltd@gmail.com')->send(new DepositNotification($transaction));
            Log::info('Deposit notification email sent successfully');
        } catch (\Exception $e) {
            Log::error('Failed to send deposit notification email: ' . $e->getMessage());
            // Don't fail the transaction if email fails
        }

        return redirect()->route('member.deposit.payment', $transaction->id)
            ->with('success', 'Deposit berhasil dibuat dengan kode: ' . $reference);
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
