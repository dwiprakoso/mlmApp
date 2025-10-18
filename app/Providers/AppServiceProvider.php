<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\View\Composers\ConfigComposer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer([
            'guest.layouts.app',
            'member.layouts.app',
            'admin.layouts.app'
        ], ConfigComposer::class);
    }
}
