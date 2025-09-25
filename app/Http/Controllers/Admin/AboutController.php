<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    public function index()
    {
        $configs = [
            'app_name' => Config::get('app_name', 'My Application'),
            'app_logo' => Config::get('app_logo', '/images/logo.png'),
            'app_description' => Config::get('app_description', 'This is a sample application built with Laravel'),
            'bank_name' => Config::get('bank_name', 'BRI'),
            'bank_account_number' => Config::get('bank_account_number', '1234567890'),
            'account_name' => Config::get('account_name', 'John Doe'),
            'payment_qr_code' => Config::get('payment_qr_code', ''),
            'team_invite_presentation' => Config::get('team_invite_presentation', '10'),
            'header_text' => Config::get('header_text', 'Investasi pertambangan'),
            'legal_name' => Config::get('legal_name', 'PT RICH KINGDOM ID'),
            'withdrawal_fee' => Config::get('withdrawal_fee', '15'),
            'whatsapp_channel' => Config::get('whatsapp_channel', ''),
            'whatsapp_number' => Config::get('whatsapp_number', '628123456790'),
        ];

        return view('admin.pages.about.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
            'payment_qr_code' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'team_invite_presentation' => 'required|numeric|min:0|max:100',
            'header_text' => 'required|string|max:255',
            'legal_name' => 'required|string|max:255',
            'withdrawal_fee' => 'required|numeric|min:0',
            'whatsapp_channel' => 'nullable|url',
            'whatsapp_number' => 'required|string|max:20|regex:/^[0-9]+$/',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update app settings
            Config::set('app_name', $request->name);
            Config::set('app_description', $request->description);

            // Handle app logo upload
            if ($request->hasFile('avatar')) {
                $oldLogo = Config::get('app_logo');
                if ($oldLogo && Storage::disk('public')->exists(str_replace('/storage/', '', $oldLogo))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $oldLogo));
                }

                $logoPath = $request->file('avatar')->store('logos', 'public');
                Config::set('app_logo', '/storage/' . $logoPath);
            }

            // Update bank settings
            Config::set('bank_name', $request->bank_name);
            Config::set('bank_account_number', $request->bank_account_number);
            Config::set('account_name', $request->account_name);

            // Handle payment QR code upload
            if ($request->hasFile('payment_qr_code')) {
                $oldQrCode = Config::get('payment_qr_code');
                if ($oldQrCode && Storage::disk('public')->exists(str_replace('/storage/', '', $oldQrCode))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $oldQrCode));
                }

                $qrCodePath = $request->file('payment_qr_code')->store('qr-codes', 'public');
                Config::set('payment_qr_code', '/storage/' . $qrCodePath);
            }

            // Update team settings
            Config::set('team_invite_presentation', $request->team_invite_presentation);

            // Update general settings
            Config::set('header_text', $request->header_text);
            Config::set('legal_name', $request->legal_name);
            Config::set('withdrawal_fee', $request->withdrawal_fee);

            // Update contact settings
            Config::set('whatsapp_channel', $request->whatsapp_channel);
            Config::set('whatsapp_number', $request->whatsapp_number);

            return redirect()->route('admin.about.index')
                ->with('success', 'App settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update app settings: ' . $e->getMessage())
                ->withInput();
        }
    }
}
