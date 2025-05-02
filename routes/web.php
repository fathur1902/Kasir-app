<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\StokItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

//pemasukan
Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan');

//pengeluaran
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');

//stok item
Route::get('/stok-item', [StokItemController::class, 'index'])->name('stok-item');