<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Exports\PengeluaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        if (!$request->stok_produk_id && !$request->nama_item) {
            return back()->withErrors(['stok_produk_id' => 'Pilih produk atau isi nama item secara manual.'])->withInput();
        }

        $namaItem = $request->nama_item ?? ($request->stok_produk_id ? null : 'Kebutuhan Tambahan');

        if (!$request->stok_produk_id) {
            $request->validate([
                'harga_satuan' => 'required|numeric|min:1',
            ]);
        }

        $harga = $request->stok_produk_id ?
            ($request->stok_produk_id ? \App\Models\StokProduk::find($request->stok_produk_id)->harga ?? 0 : 0) : ($request->harga_satuan ?? 0);

        if (!$request->stok_produk_id && $harga <= 0) {
            $harga = 1000;
        }

        $total = $request->jumlah_tambah * $harga;

        Pengeluaran::create([
            'stok_produk_id' => $request->stok_produk_id,
            'nama_item' => $namaItem,
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
        $filter = $request->input('filter', 'harian'); // Default harian
        $dateParams = [];

        if ($filter === 'harian') {
            $date = $request->input('harian_date', now()->format('Y-m-d')); // Default hari ini
            $dateParams['start_date'] = Carbon::parse($date)->startOfDay();
            $dateParams['end_date'] = Carbon::parse($date)->endOfDay();
            $filename = 'pengeluaran_harian_' . Carbon::parse($date)->format('Ymd') . '.xlsx';
        } elseif ($filter === 'mingguan') {
            $date = $request->input('mingguan_date', now()->startOfWeek()->format('Y-m-d')); // Default minggu ini
            $startDate = Carbon::parse($date)->startOfWeek();
            $dateParams['start_date'] = $startDate;
            $dateParams['end_date'] = $startDate->copy()->endOfWeek();
            $filename = 'pengeluaran_mingguan_' . $startDate->format('Ymd') . '_to_' . $dateParams['end_date']->format('Ymd') . '.xlsx';
        } else { // bulanan
            $date = $request->input('bulanan_date', now()->format('Y-m')); // Default bulan ini
            $dateParams['start_date'] = Carbon::parse($date . '-01')->startOfDay();
            $dateParams['end_date'] = Carbon::parse($date . '-01')->endOfMonth()->endOfDay();
            $filename = 'pengeluaran_bulanan_' . Carbon::parse($date . '-01')->format('Ym') . '.xlsx';
        }

        return Excel::download(new PengeluaranExport($filter, $dateParams), $filename);
    }
}
