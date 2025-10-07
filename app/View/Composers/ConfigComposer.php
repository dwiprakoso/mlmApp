<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Config;

class ConfigComposer
{
    public function compose(View $view)
    {
        $user = auth()->user();

        $view->with([
            'appName' => Config::get('app_name', 'My Application'),
            'appLogo' => Config::get('app_logo', '/assets/media/logos/default-logo.png'),
            'legalName' => Config::get('legal_name', 'Legal Name'),
            'appDescription' => Config::get('app_description', 'Description'),

            // ✅ Tambahan data user
            'currentUser' => $user,
            'userName' => $user ? $user->name : 'Guest',
            'userEmail' => $user ? $user->email : null,
            'userPhone' => $user ? $user->phone : null,
        ]);
    }
}
