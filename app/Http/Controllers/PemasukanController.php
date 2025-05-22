<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Exports\PemasukanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $transaksis = Transaksi::with('transaksiItems.stokProduk.produk')->latest()->get();

        return view('pemasukan.index', compact('transaksis'));
    }



    public function export(Request $request)
    {
        return Excel::download(new PemasukanExport($request), 'pemasukan.xlsx');
    }
}
