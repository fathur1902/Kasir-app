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
        $totalPemasukan = Transaksi::sum('total');
        return view('transaksi.index', compact('produk', 'pesanan', 'totalPemasukan'));
    }

    public function store(Request $request)
    {
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
        foreach ($request->items as $item) {
            $stokProduk = stokProduk::find($item['produk_id']);

            if (!$stokProduk) {
                return response()->json(['error' => 'Produk tidak ditemukan di stok.'], 400);
            }

            $stok = $stokProduk->jumlah;
            if ($stok < $item['jumlah']) {
                return response()->json(['error' => 'Stok produk tidak mencukupi.'], 400);
            }

            $hargaModal = $stokProduk->harga;
            $keuntunganPerItem = $hargaModal * 0.10;
            $transaksi->transaksiItems()->create([
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'total_harga' => $item['jumlah'] * $item['harga_satuan'],
                'keuntungan' => $keuntunganPerItem * $item['jumlah'],
            ]);
            $stokProduk->jumlah -= $item['jumlah'];
            $stokProduk->save();
        }
        Log::info('Keuntungan: ', [
            'produk_id' => $item['produk_id'],
            'harga_modal' => $hargaModal,
            'keuntungan_per_item' => $keuntunganPerItem,
            'jumlah' => $item['jumlah'],
            'total_keuntungan' => $keuntunganPerItem * $item['jumlah'],
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil.']);
    }

    public function preview($id)
    {
        $trx = Transaksi::with('transaksiItems.stokProduk')->findOrFail($id);

        return response()->json([
            'id' => $trx->id,
            'metode_pembayaran' => $trx->metode_pembayaran,
            'total' => $trx->total,
            'bayar' => $trx->bayar ?? $trx->total,
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
