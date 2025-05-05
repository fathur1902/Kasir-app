<?php

namespace App\Http\Controllers;

use App\Models\stokProduk;
use App\Models\Produk;
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
            'produk_id' => 'required|exists:produk,id',
            'harga' => 'required|numeric',
            'jumlah' => 'required|integer',
        ]);

        $total = $request->harga * $request->jumlah;

        StokProduk::create([
            'produk_id' => $request->produk_id,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah,
            'total' => $total,
            'profit' => 0, // nanti di-update otomatis
        ]);

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan.');
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
            'produk_id' => 'required|exists:produk,id',
            'harga' => 'required|numeric',
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

        return redirect()->route('stok-produk.index')->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = StokProduk::findOrFail($id);
        $stok->delete();

        return redirect()->back()->with('success', 'Stok berhasil dihapus.');
    }
}
