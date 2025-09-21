<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with('user')->latest()->get();
        return view('admin.pages.deposit.index', compact('deposits'));
    }

    public function edit($id)
    {
        $deposit = Deposit::with('user')->findOrFail($id);

        // Ensure approved_at is a Carbon instance jika ada
        if ($deposit->approved_at && !$deposit->approved_at instanceof \Carbon\Carbon) {
            $deposit->approved_at = \Carbon\Carbon::parse($deposit->approved_at);
        }

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

        // Buat deposit baru dengan status auto confirmed
        Deposit::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'confirmed', // Auto confirmed
            'approved_by' => auth()->id(), // ID admin yang membuat
            'approved_at' => now(),
            'proof_url' => null // Tidak ada bukti karena dibuat manual oleh admin
        ]);

        return redirect()->back()->with('success', 'Deposit berhasil ditambahkan untuk user ' . $user->name);
    }

    public function confirm($id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status !== 'waiting_confirmation') {
            return redirect()->back()->with('error', 'Deposit tidak dapat dikonfirmasi.');
        }

        $deposit->update([
            'status' => 'confirmed',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Deposit berhasil dikonfirmasi.');
    }

    public function reject(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status !== 'waiting_confirmation') {
            return redirect()->back()->with('error', 'Deposit tidak dapat ditolak.');
        }

        $deposit->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Deposit berhasil ditolak.');
    }
}
