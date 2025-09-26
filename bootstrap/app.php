<?php

// bootstrap/app.php - Simple Version

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('profit:distribute')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/profit_distribution.log'));
        // Daily summary report at 7 AM
        $schedule->command('profit:distribute --dry-run')
            ->dailyAt('07:00')
            ->appendOutputTo(storage_path('logs/profit_summary.log'));
    })
    ->create();
