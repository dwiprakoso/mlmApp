<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpPassword;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    // 1. Tampilkan form request reset password
    public function showRequestForm()
    {
        return view('guest.pages.forgot-password.index');
    }

    // 2. Process request reset - kirim OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ], [
            'phone.required' => 'No HP wajib diisi',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors([
                'phone' => 'No HP tidak terdaftar'
            ])->withInput();
        }

        if ($user->status !== 'active') {
            return back()->withErrors([
                'phone' => 'Akun tidak aktif'
            ])->withInput();
        }

        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update atau Create OTP
        OtpPassword::updateOrCreate(
            ['phone' => $request->phone],
            [
                'otp' => $otp,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMinutes(5), // Expire 5 menit
            ]
        );

        // Kirim OTP ke WhatsApp
        $whatsappService = new WhatsAppOtpService();
        $result = $whatsappService->sendOtp($request->phone, $otp);

        if (!$result['success']) {
            return back()->withErrors([
                'phone' => 'Gagal mengirim OTP: ' . $result['message']
            ])->withInput();
        }

        return redirect()
            ->route('reset-password.verify-otp', ['phone' => $request->phone])
            ->with('success', 'Kode OTP telah dikirim ke nomor WhatsApp Anda');
    }

    // 3. Tampilkan form verifikasi OTP
    public function showVerifyOtpForm(Request $request)
    {
        $phone = $request->get('phone');

        if (!$phone) {
            return redirect()->route('reset-password.request');
        }

        return view('guest.pages.verify-otp.index', compact('phone'));
    }

    // 4. Process verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ], [
            'phone.required' => 'No HP wajib diisi',
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.size' => 'Kode OTP harus 6 digit',
        ]);

        $otpRecord = OtpPassword::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid atau sudah kadaluarsa'
            ])->withInput();
        }

        // OTP valid, redirect ke form reset password
        return redirect()
            ->route('reset-password.reset', [
                'phone' => $request->phone,
                'token' => $otpRecord->id
            ]);
    }

    // 5. Tampilkan form reset password
    public function showResetForm(Request $request)
    {
        $phone = $request->get('phone');
        $token = $request->get('token');

        if (!$phone || !$token) {
            return redirect()->route('reset-password.request');
        }

        // Cek apakah token masih valid
        $otpRecord = OtpPassword::where('id', $token)
            ->where('phone', $phone)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return redirect()
                ->route('reset-password.request')
                ->withErrors(['error' => 'Sesi reset password sudah kadaluarsa']);
        }

        return view('guest.pages.reset-password.index', compact('phone', 'token'));
    }

    // 6. Process reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Validasi token
        $otpRecord = OtpPassword::where('id', $request->token)
            ->where('phone', $request->phone)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'error' => 'Sesi reset password tidak valid atau sudah kadaluarsa'
            ]);
        }

        // Update password user
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors([
                'error' => 'User tidak ditemukan'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Tandai OTP sebagai sudah digunakan
        $otpRecord->update([
            'is_used' => true
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }

    // Optional: Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors([
                'phone' => 'No HP tidak terdaftar'
            ]);
        }

        // Generate OTP baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update atau Create OTP baru
        OtpPassword::updateOrCreate(
            ['phone' => $request->phone],
            [
                'otp' => $otp,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        // Kirim OTP ke WhatsApp
        $whatsappService = new WhatsAppOtpService();
        $result = $whatsappService->sendOtp($request->phone, $otp);

        if (!$result['success']) {
            return back()->withErrors([
                'error' => 'Gagal mengirim OTP: ' . $result['message']
            ]);
        }

        return back()->with('success', 'Kode OTP baru telah dikirim');
    }
}
