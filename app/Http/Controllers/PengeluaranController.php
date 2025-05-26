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

    public function create()
    {
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'nullable|string',
            'jumlah_tambah' => 'required|integer|min:1',
            'harga_satuan' => 'nullable|numeric|min:0',
            'stok_produk_id' => 'nullable|exists:stok_produks,id',
        ]);

        // Wajib isi salah satu: stok_produk_id atau nama_item
        if (!$request->stok_produk_id && !$request->nama_item) {
            return back()->withErrors(['stok_produk_id' => 'Pilih produk atau isi nama item secara manual.'])->withInput();
        }

        $harga = $request->harga_satuan ?? 0;
        $total = $request->jumlah_tambah * $harga;

        Pengeluaran::create([
            'stok_produk_id' => $request->stok_produk_id,
            'nama_item' => $request->nama_item,
            'jumlah_tambah' => $request->jumlah_tambah,
            'harga_satuan' => $harga,
            'total' => $total,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Kebutuhan berhasil ditambahkan.');
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
