<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AlamatPengirimanController;
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

    // PELANGGAN: admin dan user
    Route::resource('pelanggan', PelangganController::class)
        ->only(['index', 'edit', 'update']);

    // PELANGGAN: khusus admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('pelanggan', PelangganController::class)
            ->only(['create', 'store', 'destroy']);
    });

    // ALAMAT PENGIRIMAN
    Route::resource('alamat', AlamatPengirimanController::class)
        ->except(['show']);
});
