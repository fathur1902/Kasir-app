<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produk = Produk::orderBy('created_at', 'desc')->get();

        // Jika ada request untuk edit (misal pakai ?edit=3)
        $editProduk = null;
        if ($request->has('edit')) {
            $editProduk = Produk::find($request->input('edit'));
        }

        return view('produk.index', compact('produk', 'editProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'singkatan' => 'required',
        ]);

        Produk::create($request->only(['nama', 'singkatan']));

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return redirect()->route('produk.index', ['edit' => $id]);
    }    

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'singkatan' => 'required',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->only(['nama', 'singkatan']));

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
