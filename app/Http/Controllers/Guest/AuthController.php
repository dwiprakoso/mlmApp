<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\ReferralUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signIn()
    {
        return view('guest.pages.sign-in.index');
    }

    public function processSignIn(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email atau No HP wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $login = $request->login;
        $password = $request->password;

        $user = User::where('email', $login)
            ->orWhere('phone', $login)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'User tidak ditemukan'
            ])->withInput();
        }

        if ($user->status !== 'active') {
            return back()->withErrors([
                'login' => 'Akun Anda tidak aktif'
            ])->withInput();
        }

        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login' => 'Password salah'
            ])->withInput();
        }

        Auth::login($user);

        if ($user->hasRole('admin')) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/member/dashboard');
    }

    public function signUp(Request $request)
    {
        $referralCode = $request->get('ref');
        $referrer = null;

        if ($referralCode) {
            $referrer = User::where('refferal_code', $referralCode)->first();
        }

        return view('guest.pages.sign-up.index', compact('referralCode', 'referrer'));
    }

    public function processSignUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'phone.required' => 'No HP wajib diisi',
            'phone.unique' => 'No HP sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active'
        ]);

        $user->assignRole('member');

        // Handle referral jika ada
        $referralCode = $request->get('ref');
        if ($referralCode) {
            $referrer = User::where('refferal_code', $referralCode)->first();

            if ($referrer) {
                ReferralUsage::create([
                    'user_referral' => $referrer->id,
                    'used_by' => $user->id
                ]);
            }
        }

        Auth::login($user);

        return redirect('/member/dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda telah logout');
    }
}
