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
        $filter = $request->input('filter', 'harian'); // Ambil filter dengan default 'harian'
        return Excel::download(new PemasukanExport($filter), 'pemasukan_' . $filter . '_' . now()->format('Ymd') . '.xlsx');
    }
}
