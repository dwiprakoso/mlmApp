<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\WithdrawRejected;
use App\Mail\WithdrawConfirmed;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdrawTransactions = Transaction::where('type', 'withdraw')
            ->with(['user', 'wallet'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.withdraw.index', compact('withdrawTransactions'));
    }

    public function show($id)
    {
        $withdraw = Transaction::where('type', 'withdraw')
            ->with(['user', 'wallet', 'approvedBy'])
            ->findOrFail($id);

        return view('admin.pages.withdraw.detail', compact('withdraw'));
    }

    public function confirm(Request $request, $id)
    {
        $withdraw = Transaction::where('type', 'withdraw')
            ->findOrFail($id);

        // Validasi status
        if (!in_array($withdraw->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Withdraw cannot be confirmed. Invalid status.');
        }

        // Validasi upload bukti transfer (WAJIB)
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'payment_proof.required' => 'Bukti transfer wajib diupload untuk konfirmasi withdraw.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format file harus: JPEG, PNG, JPG, atau GIF.',
            'payment_proof.max' => 'Ukuran file maksimal 2MB.'
        ]);

        // Delete old payment proof if exists
        if ($withdraw->payment_proof && Storage::exists($withdraw->payment_proof)) {
            Storage::delete($withdraw->payment_proof);
        }

        // Store new payment proof
        $paymentProofPath = $request->file('payment_proof')->store('payment-proofs/withdraws', 'public');

        // Update withdraw status dan payment proof
        $withdraw->update([
            'status' => 'success',
            'payment_proof' => $paymentProofPath,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'updated_at' => now()
        ]);

        // Send confirmation email
        try {
            Mail::to($withdraw->user->email)->send(new WithdrawConfirmed($withdraw));
            Log::info('Withdraw confirmation email sent', [
                'withdraw_id' => $withdraw->id,
                'user_email' => $withdraw->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send withdraw confirmation email', [
                'withdraw_id' => $withdraw->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Withdraw berhasil dikonfirmasi dengan bukti transfer.');
    }

    public function reject($id)
    {
        $withdraw = Transaction::where('type', 'withdraw')
            ->findOrFail($id);

        if (!in_array($withdraw->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Withdraw cannot be rejected. Invalid status.');
        }

        $withdraw->update([
            'status' => 'failed',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'updated_at' => now()
        ]);

        // Send rejection email
        try {
            Mail::to($withdraw->user->email)->send(new WithdrawRejected($withdraw));
            Log::info('Withdraw rejection email sent', [
                'withdraw_id' => $withdraw->id,
                'user_email' => $withdraw->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send withdraw rejection email', [
                'withdraw_id' => $withdraw->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Withdraw has been rejected.');
    }
}
