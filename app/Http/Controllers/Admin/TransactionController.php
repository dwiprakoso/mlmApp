<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'product'])
            ->where('type', 'purchase')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.transaction.index', compact('transactions'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['user', 'product'])->findOrFail($id);

            if ($transaction->status !== 'pending') {
                DB::rollBack();
                return back()->with('error', 'Transaksi ini tidak dapat disetujui. Status saat ini: ' . $transaction->status);
            }

            $currentBalance = Transaction::calculateUserBalance($transaction->user_id);

            if ($currentBalance < $transaction->amount) {
                DB::rollBack();
                return back()->with('error', 'Saldo user tidak mencukupi. Saldo: Rp ' . number_format($currentBalance, 0, ',', '.') . ', Dibutuhkan: Rp ' . number_format($transaction->amount, 0, ',', '.'));
            }

            $transaction->update([
                'status' => 'success',
                'approved_by' => Auth::id(),
            ]);

            DB::commit();

            Log::info('Transaction Approved', [
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount,
                'approved_by' => Auth::id(),
                'previous_balance' => $currentBalance,
                'new_balance' => $currentBalance - $transaction->amount,
            ]);

            return back()->with('success', 'Transaksi berhasil disetujui. Reference: ' . $transaction->reference);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Transaction Approve Error', [
                'transaction_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyetujui transaksi: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['user', 'product'])->findOrFail($id);

            if ($transaction->status !== 'pending') {
                DB::rollBack();
                return back()->with('error', 'Transaksi ini tidak dapat ditolak. Status saat ini: ' . $transaction->status);
            }

            $transaction->update([
                'status' => 'failed',
                'approved_by' => Auth::id(),
            ]);

            DB::commit();

            Log::info('Transaction Rejected', [
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount,
                'rejected_by' => Auth::id(),
            ]);

            return back()->with('success', 'Transaksi berhasil ditolak. Reference: ' . $transaction->reference);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Transaction Reject Error', [
                'transaction_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menolak transaksi: ' . $e->getMessage());
        }
    }
}
