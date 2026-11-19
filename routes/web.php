<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Modul Katalog Produk - Daftar Produk (Publik / semua role)
Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/index.php', [ProdukController::class, 'index']);

// Modul Autentikasi
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Modul Profil Pengguna (Semua pengguna terautentikasi dan aktif)
Route::middleware(['auth', 'active'])->group(function (): void {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});

// Modul Katalog Produk - Tambah & Edit (Admin & Staff; kepemilikan & kunci dicek di ProdukPolicy)
Route::middleware(['auth', 'active', 'role:admin,staff'])->group(function (): void {
    Route::get('/produk', [ProdukController::class, 'form'])->name('produk.form');
    Route::get('/produk.php', [ProdukController::class, 'form']);
    Route::get('/produk/{id}/edit', [ProdukController::class, 'form'])
        ->whereNumber('id')
        ->name('produk.edit');

    Route::post('/produk', [ProdukController::class, 'simpan'])->name('produk.simpan');
    Route::post('/produk.php', [ProdukController::class, 'simpan']);
    Route::put('/produk/{id}', [ProdukController::class, 'simpan'])
        ->whereNumber('id')
        ->name('produk.update');
});

// Modul Katalog Produk - Hapus & Kunci Produk (Khusus Admin)
Route::middleware(['auth', 'active', 'role:admin'])->group(function (): void {
    Route::post('/hapus', [ProdukController::class, 'hapus'])->name('produk.hapus');
    Route::post('/hapus.php', [ProdukController::class, 'hapus']);
    Route::delete('/produk/{id?}', [ProdukController::class, 'hapus'])
        ->whereNumber('id');
    Route::patch('/produk/{produk}/toggle-lock', [ProdukController::class, 'toggleLock'])
        ->whereNumber('produk')
        ->name('produk.toggle-lock');
        Route::middleware(['auth', 'active', 'role:admin', 'can:manage-users'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::patch('/users/{user}/password', [AdminUserController::class, 'resetPassword'])->name('users.password');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});

// Modul Katalog Produk - Detail Produk (Publik, ditempatkan setelah /produk agar tidak bentrok)
Route::get('/produk/{produk}', [ProdukController::class, 'show'])
    ->whereNumber('produk')
    ->name('produk.show');

// Modul Laporan & Ekspor Inventaris (RBAC + Gate)
Route::middleware(['auth', 'active'])->prefix('laporan')->name('laporan.')->group(function (): void {
    Route::get('/stok', [LaporanController::class, 'stok'])
        ->middleware('can:view-stock-report')
        ->name('stok');
    Route::get('/export', [LaporanController::class, 'exportCsv'])
        ->middleware('can:export-produk')
        ->name('export');
});

// Modul Manajemen Pengguna (Area Khusus Admin - Middleware Role + Gate)
Route::middleware(['auth', 'active', 'role:admin', 'can:manage-users'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });