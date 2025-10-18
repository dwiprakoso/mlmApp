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

class WithdrawController extends Controller
{
    public function index()
    {
        $withdrawTransactions = Transaction::where('type', 'withdraw')
            ->with(['user', 'wallet'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($transaction) {
                if (preg_match('/^(WD-\d+)/', $transaction->reference, $matches)) {
                    return $matches[1];
                }
                return $transaction->reference;
            })
            ->map(function ($group) {
                $mainTransaction = $group->first();

                $mainTransaction->amount = $group->sum('amount');
                $mainTransaction->withdrawal_fee = $group->sum('withdrawal_fee');
                $mainTransaction->net_amount = $mainTransaction->amount - $mainTransaction->withdrawal_fee;

                if (preg_match('/^(WD-\d+)/', $mainTransaction->reference, $matches)) {
                    $mainTransaction->main_reference = $matches[1];
                }

                $mainTransaction->allocation_details = $group->map(function ($t) {
                    return [
                        'source' => $t->source_balance_type,
                        'amount' => $t->amount,
                        'transaction_id' => $t->id
                    ];
                })->filter(fn($a) => $a['amount'] > 0);

                $mainTransaction->grouped_transaction_ids = $group->pluck('id')->toArray();

                return $mainTransaction;
            })
            ->sortByDesc('created_at')
            ->values();

        return view('admin.pages.withdraw.index', compact('withdrawTransactions'));
    }

    public function show($id)
    {
        $withdraw = Transaction::where('type', 'withdraw')
            ->with(['user', 'wallet', 'approvedBy'])
            ->findOrFail($id);

        if (preg_match('/^(WD-\d+)/', $withdraw->reference, $matches)) {
            $mainReference = $matches[1];

            $groupedTransactions = Transaction::where('type', 'withdraw')
                ->where('reference', 'LIKE', $mainReference . '%')
                ->with(['user', 'wallet'])
                ->get();

            $withdraw->amount = $groupedTransactions->sum('amount');
            $withdraw->withdrawal_fee = $groupedTransactions->sum('withdrawal_fee');
            $withdraw->net_amount = $withdraw->amount - $withdraw->withdrawal_fee;
            $withdraw->main_reference = $mainReference;

            $withdraw->allocation_details = $groupedTransactions->map(function ($t) {
                return [
                    'source' => $t->source_balance_type,
                    'amount' => $t->amount,
                    'transaction_id' => $t->id
                ];
            })->filter(fn($a) => $a['amount'] > 0);

            $withdraw->grouped_transaction_ids = $groupedTransactions->pluck('id')->toArray();
        }

        return view('admin.pages.withdraw.detail', compact('withdraw'));
    }

    public function confirm($id)
    {
        $withdraw = Transaction::where('type', 'withdraw')->findOrFail($id);

        if (!in_array($withdraw->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Withdraw cannot be confirmed. Invalid status.');
        }

        try {
            DB::beginTransaction();

            $mainReference = null;
            if (preg_match('/^(WD-\d+)/', $withdraw->reference, $matches)) {
                $mainReference = $matches[1];
            }

            $relatedTransactions = collect([$withdraw]);
            if ($mainReference) {
                $relatedTransactions = Transaction::where('type', 'withdraw')
                    ->where('reference', 'LIKE', $mainReference . '%')
                    ->get();
            }

            foreach ($relatedTransactions as $transaction) {
                $transaction->update([
                    'status' => 'success',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            try {
                Mail::to($withdraw->user->email)->send(new WithdrawConfirmed($withdraw));
            } catch (\Exception $e) {
                Log::error('Failed to send withdraw confirmation email', [
                    'main_reference' => $mainReference,
                    'error' => $e->getMessage()
                ]);
            }

            return redirect()->back()->with('success', 'Withdraw berhasil dikonfirmasi. (' . $relatedTransactions->count() . ' transaksi diupdate)');
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
        $withdraw = Transaction::where('type', 'withdraw')->findOrFail($id);

        if (!in_array($withdraw->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Withdraw cannot be rejected. Invalid status.');
        }

        try {
            DB::beginTransaction();

            $mainReference = null;
            if (preg_match('/^(WD-\d+)/', $withdraw->reference, $matches)) {
                $mainReference = $matches[1];
            }

            $relatedTransactions = collect([$withdraw]);
            if ($mainReference) {
                $relatedTransactions = Transaction::where('type', 'withdraw')
                    ->where('reference', 'LIKE', $mainReference . '%')
                    ->get();
            }

            foreach ($relatedTransactions as $transaction) {
                $transaction->update([
                    'status' => 'failed',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            try {
                Mail::to($withdraw->user->email)->send(new WithdrawRejected($withdraw));
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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $withdraw = Transaction::where('type', 'withdraw')->findOrFail($id);

            $mainReference = null;
            if (preg_match('/^(WD-\d+)/', $withdraw->reference, $matches)) {
                $mainReference = $matches[1];
            }

            $relatedTransactions = collect([$withdraw]);
            if ($mainReference) {
                $relatedTransactions = Transaction::where('type', 'withdraw')
                    ->where('reference', 'LIKE', $mainReference . '%')
                    ->get();
            }

            $transactionCount = $relatedTransactions->count();
            $userEmail = $withdraw->user->email ?? 'N/A';

            foreach ($relatedTransactions as $transaction) {
                $transaction->delete();
            }

            DB::commit();

            Log::info('Withdraw transactions deleted', [
                'main_reference' => $mainReference ?? $withdraw->reference,
                'user_email' => $userEmail,
                'transactions_deleted' => $transactionCount,
                'deleted_by' => auth()->id()
            ]);

            return redirect()->route('admin.withdraw.index')
                ->with('success', 'Withdraw berhasil dihapus. (' . $transactionCount . ' transaksi dihapus)');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting withdrawal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus withdraw: ' . $e->getMessage());
        }
    }
}
