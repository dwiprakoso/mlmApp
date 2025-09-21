<?php

use App\Http\Controllers\Admin\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\DepositController as MemberDepositController;
use App\Http\Controllers\Member\WithdrawController as MemberWithdrawController;
use App\Http\Controllers\Member\InvestController as MemberInvestController;
use App\Http\Controllers\Member\DompetController as MemberDompetController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'signIn'])->name('guest.sign-in');
    Route::post('/sign-in', [AuthController::class, 'processSignIn'])->name('guest.process-sign-in');
    Route::get('/sign-up', [AuthController::class, 'signUp'])->name('guest.sign-up');
    Route::post('/sign-up', [AuthController::class, 'processSignUp'])->name('guest.process-sign-up');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard Routes
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
        });
        // User Management Routes
        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
        // Deposit Routes
        Route::prefix('deposit')->name('deposit.')->group(function () {
            Route::get('/', [DepositController::class, 'index'])->name('index');
            Route::get('/detail/{id}', [DepositController::class, 'edit'])->name('edit');
            Route::post('/store', [DepositController::class, 'store'])->name('store');
            Route::post('/confirm/{id}', [DepositController::class, 'confirm'])->name('confirm');
            Route::post('/reject/{id}', [DepositController::class, 'reject'])->name('reject');
        });
        // Withdraw Routes
        Route::prefix('withdraw')->name('withdraw.')->group(function () {
            Route::get('/', [WithdrawController::class, 'index'])->name('index');
        });
        // Product Routes
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });
        // Report Routes
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
        });
        // Transaction Routes
        Route::prefix('transaction')->name('transaction.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
        });
        // About Routes
        Route::prefix('about')->name('about.')->group(function () {
            Route::get('/', [AboutController::class, 'index'])->name('index');
            Route::post('/update', [AboutController::class, 'update'])->name('update');
        });
    });
    Route::prefix('member')->name('member.')->group(function () {
        // Dashboard Routes
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [MemberDashboardController::class, 'index'])->name('index');
        });
        // Deposit Routes
        Route::prefix('deposit')->name('deposit.')->group(function () {
            Route::get('/', [MemberDepositController::class, 'index'])->name('index');
            Route::post('/', [MemberDepositController::class, 'store'])->name('store');
            Route::get('/payment/{id}', [MemberDepositController::class, 'payment'])->name('payment');
            Route::post('/payment/{id}/upload-proof', [MemberDepositController::class, 'uploadProof'])->name('upload-proof');
            Route::get('/log', [MemberDepositController::class, 'log'])->name('log');
        });
        // Withdraw Routes
        Route::prefix('withdraw')->name('withdraw.')->group(function () {
            Route::get('/', [MemberWithdrawController::class, 'index'])->name('index');
            Route::post('/store', [MemberWithdrawController::class, 'store'])->name('store');
            Route::get('/log', [MemberWithdrawController::class, 'log'])->name('log');
        });
        // Invest Routes
        Route::prefix('invest')->name('invest.')->group(function () {
            Route::get('/', [MemberInvestController::class, 'index'])->name('index');
            Route::get('/log', [MemberInvestController::class, 'log'])->name('log');
        });
        // Dompet Routes
        Route::prefix('dompet')->name('dompet.')->group(function () {
            Route::get('/', [MemberDompetController::class, 'index'])->name('index');
            Route::get('/detail', [MemberDompetController::class, 'detail'])->name('detail');
            Route::get('/create', [MemberDompetController::class, 'create'])->name('create');
            Route::post('/store', [MemberDompetController::class, 'store'])->name('store');
            Route::get('/edit/{wallet}', [MemberDompetController::class, 'edit'])->name('edit');
            Route::put('/update/{wallet}', [MemberDompetController::class, 'update'])->name('update');
            Route::delete('/destroy/{wallet}', [MemberDompetController::class, 'destroy'])->name('destroy');
            Route::patch('/set-primary/{wallet}', [MemberDompetController::class, 'setPrimary'])->name('setPrimary');
        });
    });
});
