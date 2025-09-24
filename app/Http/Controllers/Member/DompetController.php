<?php

namespace App\Http\Controllers\Member;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DompetController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Ambil wallet user yang login
        $wallets = Auth::user()->wallets()->orderByDesc('is_primary')->orderBy('created_at')->get();

        $balance = Transaction::calculateUserBalance(Auth::id());
        return view('member.pages.dompet.index', compact('wallets', 'balance'));
    }

    public function detail()
    {
        // Ambil wallet user yang login
        $wallets = Auth::user()->wallets()->orderByDesc('is_primary')->orderBy('created_at')->get();
        return view('member.pages.dompet.detail', compact('wallets'));
    }

    public function create()
    {
        return view('member.pages.dompet.create');
    }

    public function store(Request $request)
    {
        // Debug: lihat data yang masuk
        Log::info('Store wallet request:', $request->all());

        $validator = Validator::make($request->all(), [
            'wallet_type' => 'required|in:bank,ewallet',
            'is_primary' => 'boolean',

            // Bank validation
            'bank_name' => 'required_if:wallet_type,bank|nullable|string|max:100',
            'bank_account' => 'required_if:wallet_type,bank|nullable|string|max:50',
            'account_name' => 'required_if:wallet_type,bank|nullable|string|max:100',

            // E-wallet validation
            'ewallet_provider' => 'required_if:wallet_type,ewallet|nullable|string|max:50',
            'ewallet_number' => 'required_if:wallet_type,ewallet|nullable|string|max:20',
            'ewallet_name' => 'required_if:wallet_type,ewallet|nullable|string|max:100',

            'notes' => 'nullable|string|max:255'
        ], [
            'wallet_type.required' => 'Tipe wallet harus dipilih',
            'wallet_type.in' => 'Tipe wallet tidak valid',

            'bank_name.required_if' => 'Nama bank harus diisi',
            'bank_account.required_if' => 'Nomor rekening harus diisi',
            'account_name.required_if' => 'Nama pemegang rekening harus diisi',

            'ewallet_provider.required_if' => 'Provider e-wallet harus diisi',
            'ewallet_number.required_if' => 'Nomor e-wallet harus diisi',
            'ewallet_name.required_if' => 'Nama akun e-wallet harus diisi',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah ini wallet pertama user
        $isFirstWallet = Auth::user()->wallets()->count() === 0;
        $setPrimary = $request->boolean('is_primary') || $isFirstWallet;

        try {
            DB::beginTransaction();

            // Jika akan di-set sebagai primary, ubah wallet primary lama
            if ($setPrimary) {
                Auth::user()->wallets()->update(['is_primary' => false]);
            }

            $walletData = [
                'user_id' => Auth::id(),
                'wallet_type' => $request->wallet_type,
                'is_primary' => $setPrimary,
                'notes' => $request->notes,
            ];

            // Add type-specific data
            if ($request->wallet_type === 'bank') {
                $walletData = array_merge($walletData, [
                    'bank_name' => $request->bank_name,
                    'bank_account' => $request->bank_account,
                    'account_name' => $request->account_name,
                    'ewallet_provider' => null,
                    'ewallet_number' => null,
                    'ewallet_name' => null,
                ]);
            } else {
                $walletData = array_merge($walletData, [
                    'ewallet_provider' => $request->ewallet_provider,
                    'ewallet_number' => $request->ewallet_number,
                    'ewallet_name' => $request->ewallet_name,
                    'bank_name' => null,
                    'bank_account' => null,
                    'account_name' => null,
                ]);
            }

            $wallet = Wallet::create($walletData);

            DB::commit();
            Log::info('Wallet created successfully:', $wallet->toArray());

            return redirect()->route('member.dompet.index')
                ->with('success', 'Wallet berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating wallet:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal menyimpan wallet: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Wallet $wallet)
    {
        // Pastikan wallet milik user yang login
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        return view('member.pages.dompet.edit', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        // Pastikan wallet milik user yang login
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'wallet_type' => 'required|in:bank,ewallet',
            'is_primary' => 'boolean',

            // Bank validation
            'bank_name' => 'required_if:wallet_type,bank|nullable|string|max:100',
            'bank_account' => 'required_if:wallet_type,bank|nullable|string|max:50',
            'account_name' => 'required_if:wallet_type,bank|nullable|string|max:100',

            // E-wallet validation
            'ewallet_provider' => 'required_if:wallet_type,ewallet|nullable|string|max:50',
            'ewallet_number' => 'required_if:wallet_type,ewallet|nullable|string|max:20',
            'ewallet_name' => 'required_if:wallet_type,ewallet|nullable|string|max:100',

            'notes' => 'nullable|string|max:255'
        ], [
            'wallet_type.required' => 'Tipe wallet harus dipilih',
            'wallet_type.in' => 'Tipe wallet tidak valid',

            'bank_name.required_if' => 'Nama bank harus diisi',
            'bank_account.required_if' => 'Nomor rekening harus diisi',
            'account_name.required_if' => 'Nama pemegang rekening harus diisi',

            'ewallet_provider.required_if' => 'Provider e-wallet harus diisi',
            'ewallet_number.required_if' => 'Nomor e-wallet harus diisi',
            'ewallet_name.required_if' => 'Nama akun e-wallet harus diisi',
        ]);

        if ($validator->fails()) {
            Log::error('Update validation failed:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        $setPrimary = $request->boolean('is_primary');

        try {
            DB::beginTransaction();

            // Jika akan di-set sebagai primary, ubah wallet primary lama
            if ($setPrimary && !$wallet->is_primary) {
                Auth::user()->wallets()->where('id', '!=', $wallet->id)->update(['is_primary' => false]);
            }

            $walletData = [
                'wallet_type' => $request->wallet_type,
                'is_primary' => $setPrimary,
                'notes' => $request->notes,
            ];

            // Add type-specific data
            if ($request->wallet_type === 'bank') {
                $walletData = array_merge($walletData, [
                    'bank_name' => $request->bank_name,
                    'bank_account' => $request->bank_account,
                    'account_name' => $request->account_name,
                    'ewallet_provider' => null,
                    'ewallet_number' => null,
                    'ewallet_name' => null,
                ]);
            } else {
                $walletData = array_merge($walletData, [
                    'ewallet_provider' => $request->ewallet_provider,
                    'ewallet_number' => $request->ewallet_number,
                    'ewallet_name' => $request->ewallet_name,
                    'bank_name' => null,
                    'bank_account' => null,
                    'account_name' => null,
                ]);
            }

            $wallet->update($walletData);

            DB::commit();
            Log::info('Wallet updated successfully:', $wallet->fresh()->toArray());

            return redirect()->route('member.dompet.index')
                ->with('success', 'Wallet berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating wallet:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal memperbarui wallet: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Wallet $wallet)
    {
        // Pastikan wallet milik user yang login
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        // Jangan hapus jika ini wallet primary dan satu-satunya
        if ($wallet->is_primary && Auth::user()->wallets()->count() === 1) {
            return back()->with('error', 'Tidak dapat menghapus wallet utama terakhir!');
        }

        $wallet->delete();

        // Jika wallet yang dihapus adalah primary, set wallet lain sebagai primary
        if ($wallet->is_primary) {
            $nextWallet = Auth::user()->wallets()->first();
            if ($nextWallet) {
                $nextWallet->update(['is_primary' => true]);
            }
        }

        return redirect()->route('member.dompet.index')->with('success', 'Wallet berhasil dihapus!');
    }

    public function setPrimary(Wallet $wallet)
    {
        // Pastikan wallet milik user yang login
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        $wallet->update(['is_primary' => true]);

        return back()->with('success', 'Wallet berhasil dijadikan utama!');
    }
}
