<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\WithdrawRejected;
use App\Mail\WithdrawConfirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class WithdrawController extends Controller
{
    public function index()
    {
        // ✅ Get all withdraw transactions with grouping
        $withdrawTransactions = Transaction::where('type', 'withdraw')
            ->with(['user', 'wallet'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($transaction) {
                // Group by main reference (WD-1727699143)
                if (preg_match('/^(WD-\d+)/', $transaction->reference, $matches)) {
                    return $matches[1];
                }
                return $transaction->reference;
            })
            ->map(function ($group) {
                $mainTransaction = $group->first();

                // ✅ Override amount dengan total dari semua transaction dalam group
                $mainTransaction->amount = $group->sum('amount');
                $mainTransaction->withdrawal_fee = $group->sum('withdrawal_fee');
                $mainTransaction->net_amount = $mainTransaction->amount - $mainTransaction->withdrawal_fee;

                // Simpan main reference
                if (preg_match('/^(WD-\d+)/', $mainTransaction->reference, $matches)) {
                    $mainTransaction->main_reference = $matches[1];
                }

                // ✅ Allocation details untuk ditampilkan
                $mainTransaction->allocation_details = $group->map(function ($t) {
                    return [
                        'source' => $t->source_balance_type,
                        'amount' => $t->amount,
                        'transaction_id' => $t->id
                    ];
                })->filter(fn($a) => $a['amount'] > 0);

                // ✅ Simpan semua transaction IDs dalam group
                $mainTransaction->grouped_transaction_ids = $group->pluck('id')->toArray();

                return $mainTransaction;
            })
            ->sortByDesc('created_at')
            ->values();

        return view('admin.pages.withdraw.index', compact('withdrawTransactions'));
    }

    public function show($id)
    {
        // ✅ Find the main transaction
        $withdraw = Transaction::where('type', 'withdraw')
            ->with(['user', 'wallet', 'approvedBy'])
            ->findOrFail($id);

        // ✅ Get all related transactions in the same group
        if (preg_match('/^(WD-\d+)/', $withdraw->reference, $matches)) {
            $mainReference = $matches[1];

            $groupedTransactions = Transaction::where('type', 'withdraw')
                ->where('reference', 'LIKE', $mainReference . '%')
                ->with(['user', 'wallet'])
                ->get();

            // Calculate totals
            $withdraw->amount = $groupedTransactions->sum('amount');
            $withdraw->withdrawal_fee = $groupedTransactions->sum('withdrawal_fee');
            $withdraw->net_amount = $withdraw->amount - $withdraw->withdrawal_fee;
            $withdraw->main_reference = $mainReference;

            // Allocation details
            $withdraw->allocation_details = $groupedTransactions->map(function ($t) {
                return [
                    'source' => $t->source_balance_type,
                    'amount' => $t->amount,
                    'transaction_id' => $t->id
                ];
            })->filter(fn($a) => $a['amount'] > 0);

            // Store grouped IDs for confirm/reject
            $withdraw->grouped_transaction_ids = $groupedTransactions->pluck('id')->toArray();
        }

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

        try {
            DB::beginTransaction();

            // ✅ Get all transactions in the same group
            $mainReference = null;
            if (preg_match('/^(WD-\d+)/', $withdraw->reference, $matches)) {
                $mainReference = $matches[1];
            }

            // Get all related transactions
            $relatedTransactions = collect([$withdraw]);
            if ($mainReference) {
                $relatedTransactions = Transaction::where('type', 'withdraw')
                    ->where('reference', 'LIKE', $mainReference . '%')
                    ->get();
            }

            // Delete old payment proof if exists
            if ($withdraw->payment_proof && Storage::exists($withdraw->payment_proof)) {
                Storage::delete($withdraw->payment_proof);
            }

            // Store new payment proof
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs/withdraws', 'public');

            // ✅ Update ALL transactions in the group
            foreach ($relatedTransactions as $transaction) {
                $transaction->update([
                    'status' => 'success',
                    'payment_proof' => $paymentProofPath, // Same proof for all
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            // Send confirmation email (only once to user)
            try {
                Mail::to($withdraw->user->email)->send(new WithdrawConfirmed($withdraw));
                Log::info('Withdraw confirmation email sent', [
                    'main_reference' => $mainReference,
                    'user_email' => $withdraw->user->email,
                    'transactions_updated' => $relatedTransactions->count()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send withdraw confirmation email', [
                    'main_reference' => $mainReference,
                    'error' => $e->getMessage()
                ]);
            }

            return redirect()->back()->with('success', 'Withdraw berhasil dikonfirmasi dengan bukti transfer. (' . $relatedTransactions->count() . ' transaksi diupdate)');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error confirming withdrawal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Gagal mengkonfirmasi withdraw: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $withdraw = Transaction::where('type', 'withdraw')
            ->findOrFail($id);

        if (!in_array($withdraw->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Withdraw cannot be rejected. Invalid status.');
        }

        try {
            DB::beginTransaction();

            // ✅ Get all transactions in the same group
            $mainReference = null;
            if (preg_match('/^(WD-\d+)/', $withdraw->reference, $matches)) {
                $mainReference = $matches[1];
            }

            // Get all related transactions
            $relatedTransactions = collect([$withdraw]);
            if ($mainReference) {
                $relatedTransactions = Transaction::where('type', 'withdraw')
                    ->where('reference', 'LIKE', $mainReference . '%')
                    ->get();
            }

            // ✅ Update ALL transactions in the group to failed
            foreach ($relatedTransactions as $transaction) {
                $transaction->update([
                    'status' => 'failed',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            // Send rejection email (only once to user)
            try {
                Mail::to($withdraw->user->email)->send(new WithdrawRejected($withdraw));
                Log::info('Withdraw rejection email sent', [
                    'main_reference' => $mainReference,
                    'user_email' => $withdraw->user->email,
                    'transactions_updated' => $relatedTransactions->count()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send withdraw rejection email', [
                    'main_reference' => $mainReference,
                    'error' => $e->getMessage()
                ]);
            }

            return redirect()->back()->with('success', 'Withdraw has been rejected. (' . $relatedTransactions->count() . ' transaksi diupdate)');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error rejecting withdrawal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Gagal menolak withdraw: ' . $e->getMessage());
        }
    }
}
