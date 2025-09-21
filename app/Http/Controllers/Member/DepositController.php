<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'method' => 'required|string'
        ]);

        // Create deposit record
        $deposit = Deposit::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending'
        ]);

        // Redirect to payment page
        return redirect()->route('member.deposit.payment', $deposit->id);
    }

    public function payment($id)
    {
        $deposit = Deposit::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Payment methods with account details
        $paymentMethods = [
            'wallet_qris' => [
                'name' => 'Wallet & QRIS & E-Bank',
                'accounts' => [
                    [
                        'type' => 'BCA',
                        'number' => '1234567890',
                        'name' => 'PT EXAMPLE'
                    ],
                    [
                        'type' => 'Mandiri',
                        'number' => '0987654321',
                        'name' => 'PT EXAMPLE'
                    ],
                    [
                        'type' => 'QRIS',
                        'qr_code' => 'qris_code_image.png',
                        'name' => 'QRIS Payment'
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

        $deposit = Deposit::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Upload payment proof
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = 'deposit_proof_' . $deposit->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('deposits/proofs', $fileName, 'public');

            $deposit->update([
                'proof_url' => $filePath,
                'status' => 'waiting_confirmation'
            ]);
        }

        return redirect()->route('member.deposit.log')->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function log()
    {
        $deposits = Deposit::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.pages.deposit.log', compact('deposits'));
    }
}
