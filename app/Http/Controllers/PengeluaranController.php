<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Exports\PengeluaranExport;
use Maatwebsite\Excel\Facades\Excel;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::with('stokProduk.produk')->latest()->get();
        return view('pengeluaran.index', compact('pengeluarans'));
    }

    public function show($id)
    {
        $pengeluaran = Pengeluaran::with('stokProduk.produk')->findOrFail($id);
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    public function export()
    {
        return Excel::download(new PengeluaranExport, 'pengeluaran.xlsx');
    }
}
