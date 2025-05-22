<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\stokProduk;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardController extends Controller {
    public function index(){
        // $produk = stokProduk::with('produk')->get();
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('transaksi.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses dashboard.');
        }
        $pesanan = Transaksi::with('transaksiItems.stokProduk')->latest()->get();
        return view('dashboard', compact('pesanan'));
    }

}