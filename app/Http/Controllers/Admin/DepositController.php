<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Mail\DepositRejected;
use App\Mail\DepositConfirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Transaction::with('user')
            ->where('type', 'deposit')
            ->latest('created_at')
            ->get();

        return view('admin.pages.deposit.index', compact('deposits'));
    }

    public function edit($id)
    {
        $deposit = Transaction::with('user')
            ->where('type', 'deposit')
            ->findOrFail($id);

        return view('admin.pages.deposit.detail', compact('deposit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User dengan nomor HP tersebut tidak ditemukan.');
        }

        $reference = 'DEP-' . strtoupper(uniqid());

        Transaction::create([
            'user_id' => $user->id,
            'product_id' => null,
            'reference' => $reference,
            'amount' => $request->amount,
            'type' => 'deposit',
            'status' => 'success',
            'payment_method' => $request->method,
            'payment_proof' => null,
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Deposit berhasil ditambahkan untuk user ' . $user->name);
    }

    public function confirm($id)
    {
        $deposit = Transaction::where('type', 'deposit')->findOrFail($id);

        if (!in_array($deposit->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Deposit tidak dapat dikonfirmasi.');
        }

        $deposit->update([
            'status' => 'success',
            'approved_by' => auth()->id(),
        ]);

        Log::info('Deposit confirmed', [
            'deposit_id' => $deposit->id,
            'user_id' => $deposit->user_id,
            'amount' => $deposit->amount
        ]);

        try {
            Mail::to($deposit->user->email)->send(new DepositConfirmed($deposit));
        } catch (\Exception $e) {
            Log::error('Failed to send deposit confirmation email', [
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage()
            ]);
        }

        $referralUsage = DB::table('referral_usages')
            ->where('used_by', $deposit->user_id)
            ->first();

        if ($referralUsage) {
            $isFirstDeposit = $referralUsage->deposit_count == 0;

            if ($isFirstDeposit) {
                DB::table('referral_usages')
                    ->where('used_by', $deposit->user_id)
                    ->increment('deposit_count');
            }

            $commissionRate = DB::table('configs')
                ->where('key', 'team_invite_presentation')
                ->value('value');

            if ($commissionRate) {
                $commissionRate = json_decode($commissionRate, true) ?? $commissionRate;
                $commissionRate = (float) $commissionRate;

                if ($commissionRate > 0 && $commissionRate <= 100) {
                    $currentUserId = $deposit->user_id;
                    $currentCommissionAmount = $deposit->amount * ($commissionRate / 100);
                    $level = 1;
                    $maxLevel = 10;

                    while ($level <= $maxLevel) {
                        $referralUsageData = DB::table('referral_usages')
                            ->where('used_by', $currentUserId)
                            ->first();

                        if (!$referralUsageData) {
                            break;
                        }

                        $uplineUserId = $referralUsageData->user_referral;
                        $commissionForUpline = $currentCommissionAmount;

                        try {
                            Transaction::create([
                                'user_id' => $uplineUserId,
                                'wallet_id' => null,
                                'product_id' => null,
                                'reference' => 'COM-L' . $level . '-' . time() . '-' . $deposit->user_id,
                                'amount' => $commissionForUpline,
                                'type' => 'commission',
                                'withdrawal_fee' => 0,
                                'status' => 'success',
                                'payment_method' => 'referral_commission',
                                'payment_proof' => null,
                            ]);

                            Log::info('Commission transaction created', [
                                'level' => $level,
                                'amount' => $commissionForUpline,
                                'upline_id' => $uplineUserId
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to create commission transaction', [
                                'level' => $level,
                                'upline_id' => $uplineUserId,
                                'error' => $e->getMessage()
                            ]);
                        }

                        $currentCommissionAmount = $commissionForUpline * ($commissionRate / 100);
                        $currentUserId = $uplineUserId;
                        $level++;

                        if ($currentCommissionAmount < 1) {
                            break;
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Deposit berhasil dikonfirmasi.');
    }
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $deposit = Transaction::where('type', 'deposit')->findOrFail($id);

        if (!in_array($deposit->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Deposit tidak dapat ditolak.');
        }

        $deposit->update([
            'status' => 'failed',
            'approved_by' => auth()->id(),
        ]);

        Log::info('Deposit rejected', [
            'deposit_id' => $deposit->id,
            'user_id' => $deposit->user_id,
            'amount' => $deposit->amount,
            'reason' => $request->reason,
            'rejected_by' => auth()->id()
        ]);

        try {
            Mail::to($deposit->user->email)->send(new DepositRejected($deposit, $request->reason));
        } catch (\Exception $e) {
            Log::error('Failed to send deposit rejection email', [
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Deposit berhasil ditolak.');
    }

    public function destroy($id)
    {
        try {
            $deposit = Transaction::where('type', 'deposit')->findOrFail($id);

            if ($deposit->status == 'success') {
                return redirect()->back()->with('error', 'Deposit yang sudah dikonfirmasi tidak dapat dihapus.');
            }

            $deposit->delete();

            Log::info('Deposit deleted', [
                'deposit_id' => $id,
                'user_id' => $deposit->user_id,
                'deleted_by' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Deposit berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete deposit', [
                'deposit_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus deposit.');
        }
    }
}
