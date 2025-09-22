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

        $deposit->update([
            'status' => 'success',
            'approved_by' => auth()->id(),
        ]);

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
