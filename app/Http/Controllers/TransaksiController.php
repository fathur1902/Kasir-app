<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\stokProduk;
use Illuminate\Support\Facades\Log;


class TransaksiController extends Controller
{
    public function index()
    {
        $produk = stokProduk::with('produk')->get();
        $pesanan = Transaksi::with('transaksiItems.stokProduk')->latest()->get();
        return view('transaksi.index', compact('produk', 'pesanan'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'metode_pembayaran' => 'required|string|in:cash,qris',
            'total' => 'required|numeric',
            'bayar' => 'required|numeric',
            'items' => 'required|array',
            'items.*.produk_id' => 'required|exists:stok_produks,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        $transaksi = Transaksi::create([
            'metode_pembayaran' => $request->metode_pembayaran,
            'total' => $request->total,
            'bayar' => $request->bayar,
            'kembalian' => $request->bayar - $request->total,
        ]);

        if (!$transaksi || !$transaksi->id) {
            return response()->json(['error' => 'Gagal membuat transaksi.'], 500);
        }

        // Proses setiap item dalam transaksi
        foreach ($request->items as $item) {
            // Simpan detail transaksi item
            $transaksi->transaksiItems()->create([
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'total_harga' => $item['jumlah'] * $item['harga_satuan'],
            ]);

            // Cari stok produk berdasarkan id
            $stokProduk = StokProduk::find($item['produk_id']);

            if (!$stokProduk) {
                return response()->json(['error' => 'Produk tidak ditemukan di stok.'], 400);
            }

            $stok = $stokProduk->jumlah;

            // Cek apakah stok cukup
            if ($stok < $item['jumlah']) {
                return response()->json(['error' => 'Stok produk tidak mencukupi.'], 400);
            }

            // Kurangi stok produk
            $stokProduk->jumlah -= $item['jumlah'];
            $stokProduk->save();
        }

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil.']);
    }

    public function preview($id)
    {
        $trx = Transaksi::with('transaksiItems.stokProduk')->findOrFail($id);

        return response()->json([
            'id' => $trx->id,
            'metode_pembayaran' => $trx->metode_pembayaran,
            'total' => $trx->total,
            'items' => $trx->transaksiItems->map(function ($item) {
                return [
                    'produk' => ['nama' => $item->stokProduk->produk->nama ?? 'Tidak diketahui'],
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->harga_satuan,
                ];
            }),
        ]);
    }
}
