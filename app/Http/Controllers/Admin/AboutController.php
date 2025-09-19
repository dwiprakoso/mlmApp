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
            'app_name' => Config::get('app_name', 'Default App Name'),
            'app_logo' => Config::get('app_logo', '/images/default-logo.png'),
            'app_description' => Config::get('app_description', 'Default app description'),
        ];

        return view('admin.pages.about.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Config::set('app_name', $request->name);

            Config::set('app_description', $request->description);

            if ($request->hasFile('avatar')) {
                $oldLogo = Config::get('app_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                $logoPath = $request->file('avatar')->store('logos', 'public');
                Config::set('app_logo', '/storage/' . $logoPath);
            }

            return redirect()->route('admin.about.index')
                ->with('success', 'App settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update app settings: ' . $e->getMessage())
                ->withInput();
        }
    }
}
