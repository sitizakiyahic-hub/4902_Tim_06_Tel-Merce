<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\EkspedisiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AlamatPengirimanController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// HALAMAN AWAL
Route::redirect('/', '/dashboard');

// LOGIN
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// HALAMAN SETELAH LOGIN
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // DASHBOARD TEL-MERCE
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

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

    // PELANGGAN
    Route::resource('pelanggan', PelangganController::class)
        ->only(['index', 'edit', 'update']);

    Route::middleware('role:admin')->group(function () {
        Route::resource('pelanggan', PelangganController::class)
            ->only(['create', 'store', 'destroy']);
    });

    // ALAMAT PENGIRIMAN
    Route::resource('alamat', AlamatPengirimanController::class)
        ->except(['show']);

    // KATALOG PRODUK
    Route::get('/produk', [ProdukController::class, 'index'])
        ->name('produk.index');

    Route::middleware('role:admin')->group(function () {
        Route::resource('produk', ProdukController::class)
            ->except(['index', 'show']);
    });

});
