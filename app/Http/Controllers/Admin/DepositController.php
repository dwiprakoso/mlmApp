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
        // Ambil transaksi dengan type 'deposit' saja
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

        // Cari user berdasarkan nomor HP
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User dengan nomor HP tersebut tidak ditemukan.');
        }

        // Generate reference number
        $reference = 'DEP-' . strtoupper(uniqid());

        // Buat deposit baru dengan status success (auto confirmed)
        Transaction::create([
            'user_id' => $user->id,
            'product_id' => null, // Null untuk deposit
            'reference' => $reference,
            'amount' => $request->amount,
            'type' => 'deposit',
            'status' => 'success', // Auto confirmed untuk manual deposit
            'payment_method' => $request->method,
            'payment_proof' => null, // Tidak ada bukti karena dibuat manual oleh admin
            'approved_by' => auth()->id(), // ID admin yang membuat
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

        // Send confirmation email
        try {
            Mail::to($deposit->user->email)->send(new DepositConfirmed($deposit));
            Log::info('Deposit confirmation email sent', [
                'deposit_id' => $deposit->id,
                'user_email' => $deposit->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit confirmation email', [
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage()
            ]);
        }

        $referralUsage = DB::table('referral_usages')
            ->where('used_by', $deposit->user_id)
            ->first();
        Log::info('Referral usage check', [
            'user_id' => $deposit->user_id,
            'referral_usage_found' => $referralUsage ? 'yes' : 'no',
            'referral_usage' => $referralUsage
        ]);

        if ($referralUsage) {
            // Cek apakah ini first deposit untuk update is_deposit flag
            $successfulDepositCount = Transaction::where('user_id', $deposit->user_id)
                ->where('type', 'deposit')
                ->where('status', 'success')
                ->where('id', '!=', $deposit->id)
                ->count();

            Log::info('Successful deposit count', [
                'user_id' => $deposit->user_id,
                'count' => $successfulDepositCount,
                'is_first_deposit' => $successfulDepositCount == 0 ? 'yes' : 'no'
            ]);

            // Update is_deposit flag hanya di first deposit
            if ($successfulDepositCount == 0) {
                DB::table('referral_usages')
                    ->where('used_by', $deposit->user_id)
                    ->update([
                        'is_deposit' => true,
                        'updated_at' => now()
                    ]);

                Log::info('Referral usage updated - is_deposit set to true', [
                    'user_id' => $deposit->user_id
                ]);
            }

            // PROSES KOMISI UNTUK SETIAP DEPOSIT (tidak hanya first deposit)
            $commissionRate = DB::table('configs')
                ->where('key', 'team_invite_presentation')
                ->value('value');

            Log::info('Commission rate check', [
                'raw_commission_rate' => $commissionRate,
                'commission_rate_type' => gettype($commissionRate)
            ]);

            if ($commissionRate) {
                $commissionRate = json_decode($commissionRate, true) ?? $commissionRate;
                $commissionRate = (float) $commissionRate;

                Log::info('Commission rate converted', [
                    'converted_rate' => $commissionRate,
                    'is_valid_percentage' => ($commissionRate > 0 && $commissionRate <= 100) ? 'yes' : 'no'
                ]);

                if ($commissionRate > 0 && $commissionRate <= 100) {
                    // ========== MULTI-LEVEL COMMISSION SYSTEM ==========
                    $currentUserId = $deposit->user_id;
                    $currentCommissionAmount = $deposit->amount * ($commissionRate / 100);
                    $level = 1;
                    $maxLevel = 10;

                    Log::info('Starting multi-level commission calculation', [
                        'initial_deposit_amount' => $deposit->amount,
                        'commission_rate' => $commissionRate,
                        'depositor_user_id' => $currentUserId,
                        'deposit_number' => $successfulDepositCount + 1
                    ]);

                    // Loop untuk distribusi komisi ke upline hierarchy
                    while ($level <= $maxLevel) {
                        // Cari siapa yang punya referral code yang dipakai oleh currentUserId
                        $referralUsageData = DB::table('referral_usages')
                            ->where('used_by', $currentUserId)
                            ->first();

                        if (!$referralUsageData) {
                            Log::info('No more upline found, stopping commission distribution', [
                                'level' => $level,
                                'last_user_id' => $currentUserId
                            ]);
                            break;
                        }

                        $uplineUserId = $referralUsageData->user_referral;

                        // Hitung komisi untuk upline ini
                        $commissionForUpline = $currentCommissionAmount;

                        Log::info('Commission calculation for level', [
                            'level' => $level,
                            'upline_user_id' => $uplineUserId,
                            'downline_user_id' => $currentUserId,
                            'commission_amount' => $commissionForUpline,
                            'base_amount' => $level == 1 ? $deposit->amount : $currentCommissionAmount
                        ]);

                        // Buat transaction komisi untuk upline
                        try {
                            $commissionTransaction = Transaction::create([
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

                            Log::info('Commission transaction created successfully', [
                                'level' => $level,
                                'commission_transaction_id' => $commissionTransaction->id,
                                'amount' => $commissionForUpline,
                                'for_user_id' => $uplineUserId,
                                'from_user_id' => $currentUserId
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to create commission transaction', [
                                'level' => $level,
                                'upline_user_id' => $uplineUserId,
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                        }

                        // Untuk level berikutnya, komisnya adalah persentase dari komisi level ini
                        $currentCommissionAmount = $commissionForUpline * ($commissionRate / 100);
                        $currentUserId = $uplineUserId;
                        $level++;

                        // Stop jika komisi sudah terlalu kecil (opsional, untuk optimasi)
                        if ($currentCommissionAmount < 1) {
                            Log::info('Commission amount too small, stopping', [
                                'level' => $level,
                                'amount' => $currentCommissionAmount
                            ]);
                            break;
                        }
                    }

                    if ($level > $maxLevel) {
                        Log::warning('Reached maximum level limit', [
                            'max_level' => $maxLevel
                        ]);
                    }

                    Log::info('Multi-level commission distribution completed', [
                        'total_levels_processed' => $level - 1,
                        'initial_deposit' => $deposit->amount
                    ]);
                    // ========== END MULTI-LEVEL COMMISSION SYSTEM ==========

                } else {
                    Log::warning('Invalid commission rate', [
                        'commission_rate' => $commissionRate
                    ]);
                }
            } else {
                Log::warning('No commission rate found in config');
            }
        } else {
            Log::info('No referral usage found for user', [
                'user_id' => $deposit->user_id
            ]);
        }

        return redirect()->back()->with('success', 'Deposit berhasil dikonfirmasi.');
    }
    public function destroy($id)
    {
        try {
            $deposit = Transaction::where('type', 'deposit')->findOrFail($id);

            // Optional: Tambahkan validasi jika deposit sudah dikonfirmasi tidak bisa dihapus
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
