<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\StokProdukController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiItemController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Halaman Welcome (dapat diubah sesuai kebutuhan)
Route::get('/', function () {
    return view('auth.login');
});

// Halaman Login dan Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Halaman Dashboard (hanya untuk yang sudah login dan terverifikasi)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Halaman Lupa Password (bisa diakses tanpa login)
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');

// Halaman Reset Password (bisa diakses melalui link di email)
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


// Rute Register (hanya bisa diakses oleh admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Rute Profile (hanya bisa diakses oleh yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk Pemasukan (hanya bisa diakses oleh yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan');
    Route::get('/pemasukan/export', [PemasukanController::class, 'export'])->name('pemasukan.export');
});

// Rute untuk Pengeluaran (hanya bisa diakses oleh yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
});

// Rute untuk Stok Produk (hanya bisa diakses oleh yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/stok-produk', [StokProdukController::class, 'index'])->name('stok.index');
    Route::get('/stok-produk/create', [StokProdukController::class, 'create'])->name('stok.create');
    Route::post('/stok-produk', [StokProdukController::class, 'store'])->name('stok.store');
    Route::get('/stok-produk/{id}/edit', [StokProdukController::class, 'edit'])->name('stok.edit');
    Route::put('/stok-produk/{id}', [StokProdukController::class, 'update'])->name('stok.update');
    Route::delete('/stok-produk/{id}', [StokProdukController::class, 'destroy'])->name('stok.destroy');
});

// Rute untuk Produk (hanya bisa diakses oleh yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});

// Rute untuk Transaksi (hanya bisa diakses oleh yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{id}/preview', [TransaksiController::class, 'preview'])->name('transaksi.preview');
});

// Pengaturan User (ganti password)
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/settings/password', [UserController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::get('/settings/password/{id}/edit', [UserController::class, 'editPassword'])->name('settings.editPassword');
    Route::delete('/settings/user/{id}', [UserController::class, 'deleteUser'])->name('settings.deleteUser');
    Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('users.update');
});

// Auth Routes (Jika menggunakan Breeze, rute ini sudah otomatis terpasang)
require __DIR__ . '/auth.php';
