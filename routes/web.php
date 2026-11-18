<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\EkspedisiController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/vouchers');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // VOUCHER
    Route::get('/vouchers', [VoucherController::class, 'index'])
        ->name('vouchers.index');

    Route::middleware('role:admin')->group(function () {
        Route::resource('vouchers', VoucherController::class)
            ->except(['index', 'show']);
    });

    // EKSPEDISI
    Route::get('/ekspedisi', [EkspedisiController::class, 'index'])
        ->name('ekspedisi.index');

    Route::middleware('role:admin')->group(function () {
        Route::resource('ekspedisi', EkspedisiController::class)
            ->except(['index', 'show']);
    });

});