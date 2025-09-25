<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

        // Sesuai dengan status di tabel transactions
        if (!in_array($deposit->status, ['pending', 'waiting_confirmation'])) {
            return redirect()->back()->with('error', 'Deposit tidak dapat dikonfirmasi.');
        }

        // Update status deposit
        $deposit->update([
            'status' => 'success',
            'approved_by' => auth()->id(),
        ]);

        // Cek apakah user yang deposit pernah menggunakan referral code
        $referralUsage = DB::table('referral_usages')
            ->where('used_by', $deposit->user_id)
            ->first();

        if ($referralUsage) {
            // Cek apakah ini deposit pertama dengan status success
            $successfulDepositCount = Transaction::where('user_id', $deposit->user_id)
                ->where('type', 'deposit')
                ->where('status', 'success')
                ->where('id', '!=', $deposit->id) // Exclude deposit yang baru saja dikonfirmasi
                ->count();

            // Jika ini adalah deposit success pertama (belum pernah ada deposit success sebelumnya)
            if ($successfulDepositCount == 0) {
                // Ambil persentase commission dari config
                $commissionRate = DB::table('configs')
                    ->where('key', 'team_invite_presentation')
                    ->value('value');

                if ($commissionRate) {
                    // Hitung commission amount
                    $commissionAmount = $deposit->amount * ($commissionRate / 100);

                    // Buat transaction commission untuk user pemilik referral code
                    Transaction::create([
                        'user_id' => $referralUsage->user_referral, // User pemilik referral code
                        'wallet_id' => null, // Sesuaikan dengan kebutuhan
                        'product_id' => null,
                        'reference' => 'REF-COMM-' . time() . '-' . $deposit->user_id,
                        'amount' => $commissionAmount,
                        'type' => 'commission',
                        'withdrawal_fee' => 0,
                        'status' => 'success',
                        'payment_method' => 'referral_commission',
                        'payment_proof' => null,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Deposit berhasil dikonfirmasi.');
    }

    public function reject(Request $request, $id)
    {
        $deposit = Transaction::where('type', 'deposit')->findOrFail($id);

        $deposit->update([
            'status' => 'failed',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Deposit berhasil ditolak.');
    }
}
