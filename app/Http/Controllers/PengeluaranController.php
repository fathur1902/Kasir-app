<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Exports\PengeluaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

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

    public function export(Request $request)
    {
        $filter = $request->input('filter', 'harian'); // Ambil filter dengan default 'harian'
        return Excel::download(new PengeluaranExport($filter), 'pengeluaran_' . $filter . '_' . now()->format('Ymd') . '.xlsx');
    }
}
