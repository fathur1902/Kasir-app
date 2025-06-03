<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Exports\PemasukanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $transaksis = Transaksi::with('transaksiItems.stokProduk.produk')->latest()->get();
        return view('pemasukan.index', compact('transaksis'));
    }

    public function export(Request $request)
    {
        $filter = $request->input('filter', 'harian'); // Default harian
        $dateParams = [];

        if ($filter === 'harian') {
            $date = $request->input('harian_date', now()->format('Y-m-d')); // Default hari ini
            $dateParams['start_date'] = Carbon::parse($date)->startOfDay();
            $dateParams['end_date'] = Carbon::parse($date)->endOfDay();
            $filename = 'pemasukan_harian_' . Carbon::parse($date)->format('Ymd') . '.xlsx';
        } elseif ($filter === 'mingguan') {
            $date = $request->input('mingguan_date', now()->startOfWeek()->format('Y-m-d')); // Default minggu ini
            $startDate = Carbon::parse($date)->startOfWeek();
            $dateParams['start_date'] = $startDate;
            $dateParams['end_date'] = $startDate->copy()->endOfWeek();
            $filename = 'pemasukan_mingguan_' . $startDate->format('Ymd') . '_to_' . $dateParams['end_date']->format('Ymd') . '.xlsx';
        } else { // bulanan
            $date = $request->input('bulanan_date', now()->format('Y-m')); // Default bulan ini
            $dateParams['start_date'] = Carbon::parse($date . '-01')->startOfDay();
            $dateParams['end_date'] = Carbon::parse($date . '-01')->endOfMonth()->endOfDay();
            $filename = 'pemasukan_bulanan_' . Carbon::parse($date . '-01')->format('Ym') . '.xlsx';
        }

        return Excel::download(new PemasukanExport($filter, $dateParams), $filename);
    }
}
