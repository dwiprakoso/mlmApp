<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\AuthController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [AuthController::class, 'index'])->name('guest.index');

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard Routes
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });
}); 

// Single Route Example
//     Route::get('/', [CampaignController::class, 'index'])->name('super-admin.campaign.index');
// Grouped Route Example
// Route::prefix('campaign')->group(function () {
//     Route::get('/', [CampaignController::class, 'index'])->name('super-admin.campaign.index');
//     Route::post('/store', [CampaignController::class, 'store'])->name('super-admin.campaign.store');
//     Route::get('/log', [CampaignController::class, 'Log'])->name('super-admin.campaign.log');
//     Route::post('/retry-message/{logId}', [CampaignController::class, 'retryMessage'])->name('super-admin.retry.message');
// });
