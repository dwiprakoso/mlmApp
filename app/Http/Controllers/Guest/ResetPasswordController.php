<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpPassword;
use App\Mail\ResetPasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('guest.pages.forgot-password.index');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar'
            ])->withInput();
        }

        if ($user->status !== 'active') {
            return back()->withErrors([
                'email' => 'Akun tidak aktif'
            ])->withInput();
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP
        OtpPassword::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        // Kirim email
        try {
            Mail::to($request->email)->send(new ResetPasswordOtp($otp, $user->name));

            return redirect()
                ->route('reset-password.verify-otp', ['email' => $request->email])
                ->with('success', 'Kode OTP telah dikirim ke email Anda');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Gagal mengirim OTP: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->get('email');

        if (!$email) {
            return redirect()->route('reset-password.request');
        }

        return view('guest.pages.verify-otp.index', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.size' => 'Kode OTP harus 6 digit',
        ]);

        $otpRecord = OtpPassword::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid atau sudah kadaluarsa'
            ])->withInput();
        }

        return redirect()
            ->route('reset-password.reset', [
                'email' => $request->email,
                'token' => $otpRecord->id
            ]);
    }

    public function showResetForm(Request $request)
    {
        $email = $request->get('email');
        $token = $request->get('token');

        if (!$email || !$token) {
            return redirect()->route('reset-password.request');
        }

        $otpRecord = OtpPassword::where('id', $token)
            ->where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return redirect()
                ->route('reset-password.request')
                ->withErrors(['error' => 'Sesi reset password sudah kadaluarsa']);
        }

        return view('guest.pages.reset-password.index', compact('email', 'token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $otpRecord = OtpPassword::where('id', $request->token)
            ->where('email', $request->email)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'error' => 'Sesi reset password tidak valid atau sudah kadaluarsa'
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'error' => 'User tidak ditemukan'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $otpRecord->update([
            'is_used' => true
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar'
            ]);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpPassword::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        try {
            Mail::to($request->email)->send(new ResetPasswordOtp($otp, $user->name));

            return back()->with('success', 'Kode OTP baru telah dikirim');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal mengirim OTP: ' . $e->getMessage()
            ]);
        }
    }
}
