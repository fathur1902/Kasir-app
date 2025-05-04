<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;


class TransaksiController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('transaksi.index', compact('produk'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $transaksi = Transaksi::create([
                'tanggal' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'total' => $request->total,
                'bayar' => $request->bayar,
                'kembalian' => $request->bayar - $request->total,
            ]);

            foreach ($request->items as $item) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total_harga' => $item['jumlah'] * $item['harga_satuan'],
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi.');
        }
    }
}
