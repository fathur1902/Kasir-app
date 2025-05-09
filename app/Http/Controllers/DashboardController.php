<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\stokProduk;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardController extends Controller {
    public function index(){
        // $produk = stokProduk::with('produk')->get();
        $pesanan = Transaksi::with('transaksiItems.stokProduk')->latest()->get();
        return view('dashboard', compact('pesanan'));
    }

}