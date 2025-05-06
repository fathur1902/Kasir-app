<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\StokProdukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('Auth.login');
})->name('login');

//Login
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

//Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//pemasukan
Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan');

//pengeluaran
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');

//stok item
Route::get('/stok-produk', [StokProdukController::class, 'index'])->name('stok.index');
Route::get('/stok-produk/create', [StokProdukController::class, 'create'])->name('stok.create');
Route::post('/stok-produk', [StokProdukController::class, 'store'])->name('stok.store');
Route::get('/stok-produk/{id}/edit', [StokProdukController::class, 'edit'])->name('stok.edit');
Route::put('/stok-produk/{id}', [StokProdukController::class, 'update'])->name('stok.update');
Route::delete('/stok-produk/{id}', [StokProdukController::class, 'destroy'])->name('stok.destroy');

//produk
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

//transaksi
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

//Pengaturan
Route::get('/settings', [UserController::class, 'settings'])->name('settings');
Route::post('/settings/password', [UserController::class, 'updatePassword'])->name('settings.updatePassword');
