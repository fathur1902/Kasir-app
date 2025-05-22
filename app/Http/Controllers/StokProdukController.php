<?php

namespace App\Http\Controllers;

use App\Models\stokProduk;
use App\Models\Produk;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;


class StokProdukController extends Controller
{
    public function index()
    {
        $stokList = StokProduk::with('produk')->orderBy('created_at', 'desc')->get();
        $produkList = Produk::all();
        return view('stok.index', compact('stokList', 'produkList'));
    }

    public function create()
    {
        $produkList = Produk::all();
        return view('stok.create', compact('produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'harga' => 'required|numeric',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Cari stok produk yang sudah ada
        $stok = StokProduk::where('produk_id', $request->produk_id)->first();

        $totalTambah = $request->harga * $request->jumlah;

        if ($stok) {
            // Update stok dan harga, total dan profit (profit tetap 0 dulu)
            $stok->jumlah += $request->jumlah;
            $stok->harga = $request->harga;
            $stok->total = $stok->harga * $stok->jumlah;
            $stok->profit = 0; // update sesuai kebutuhan
            $stok->save();
        } else {
            // Buat stok baru
            $stok = StokProduk::create([
                'produk_id' => $request->produk_id,
                'harga' => $request->harga,
                'jumlah' => $request->jumlah,
                'total' => $totalTambah,
                'profit' => 0,
            ]);
        }

        // Simpan ke tabel pengeluaran
        Pengeluaran::create([
            'stok_produk_id' => $stok->id,
            'jumlah_tambah' => $request->jumlah,
            'total' => $totalTambah,
        ]);

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan dan pengeluaran tercatat!');
    }

    public function edit($id)
    {
        $editStok = StokProduk::findOrFail($id);
        $produkList = Produk::all();
        return view('stok.edit', compact('editStok', 'produkList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'harga' => 'required|integer',
            'jumlah' => 'required|integer',
        ]);

        $stok = StokProduk::findOrFail($id);
        $total = $request->harga * $request->jumlah;

        $stok->update([
            'produk_id' => $request->produk_id,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah,
            'total' => $total,
            'profit' => 0, // sementara 0
        ]);

        return redirect()->route('stok.index')->with('success', 'Stok berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $stok = StokProduk::findOrFail($id);
        $stok->delete();

        return redirect()->back()->with('success', 'Stok berhasil dihapus!');
    }
}
