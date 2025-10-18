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
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        if (substr($phone, 0, 3) === '+62') {
            $phone = substr($phone, 1);
        }

        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    public function showRequestForm()
    {
        return view('guest.pages.forgot-password.index');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ], [
            'phone.required' => 'No HP wajib diisi',
        ]);

        $normalizedPhone = $this->normalizePhone($request->phone);

        $user = User::where(function ($query) use ($request, $normalizedPhone) {
            $query->where('phone', $request->phone)
                ->orWhere('phone', $normalizedPhone)
                ->orWhere('phone', '0' . substr($normalizedPhone, 2));
        })->first();

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

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpPassword::updateOrCreate(
            ['phone' => $normalizedPhone],
            [
                'otp' => $otp,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        $whatsappService = new WhatsAppOtpService();
        $result = $whatsappService->sendOtp($normalizedPhone, $otp);

        if (!$result['success']) {
            return back()->withErrors([
                'phone' => 'Gagal mengirim OTP: ' . $result['message']
            ])->withInput();
        }

        return redirect()
            ->route('reset-password.verify-otp', ['phone' => $normalizedPhone])
            ->with('success', 'Kode OTP telah dikirim ke nomor WhatsApp Anda');
    }

    public function showVerifyOtpForm(Request $request)
    {
        $phone = $request->get('phone');

        if (!$phone) {
            return redirect()->route('reset-password.request');
        }

        return view('guest.pages.verify-otp.index', compact('phone'));
    }

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

        return redirect()
            ->route('reset-password.reset', [
                'phone' => $request->phone,
                'token' => $otpRecord->id
            ]);
    }

    public function showResetForm(Request $request)
    {
        $phone = $request->get('phone');
        $token = $request->get('token');

        if (!$phone || !$token) {
            return redirect()->route('reset-password.request');
        }

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

        $normalizedPhone = $this->normalizePhone($request->phone);

        $otpRecord = OtpPassword::where('id', $request->token)
            ->where('phone', $normalizedPhone)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'error' => 'Sesi reset password tidak valid atau sudah kadaluarsa'
            ]);
        }

        $user = User::where(function ($query) use ($request, $normalizedPhone) {
            $query->where('phone', $request->phone)
                ->orWhere('phone', $normalizedPhone)
                ->orWhere('phone', '0' . substr($normalizedPhone, 2));
        })->first();

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
            'phone' => 'required|string',
        ]);

        $normalizedPhone = $this->normalizePhone($request->phone);

        $user = User::where(function ($query) use ($request, $normalizedPhone) {
            $query->where('phone', $request->phone)
                ->orWhere('phone', $normalizedPhone)
                ->orWhere('phone', '0' . substr($normalizedPhone, 2));
        })->first();

        if (!$user) {
            return back()->withErrors([
                'phone' => 'No HP tidak terdaftar'
            ]);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpPassword::updateOrCreate(
            ['phone' => $normalizedPhone],
            [
                'otp' => $otp,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        $whatsappService = new WhatsAppOtpService();
        $result = $whatsappService->sendOtp($normalizedPhone, $otp);

        if (!$result['success']) {
            return back()->withErrors([
                'error' => 'Gagal mengirim OTP: ' . $result['message']
            ]);
        }

        return back()->with('success', 'Kode OTP baru telah dikirim');
    }
}
