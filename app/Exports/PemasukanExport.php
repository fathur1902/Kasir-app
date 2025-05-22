<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PemasukanExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaksi::query();

        // Filter data berdasarkan request (harian/mingguan/bulanan)
        if ($this->request->filter == 'harian') {
            $query->whereDate('created_at', now());
        } elseif ($this->request->filter == 'mingguan') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->request->filter == 'bulanan') {
            $query->whereMonth('created_at', now()->month);
        }

        $data = $query->with('transaksiItems.stokProduk.produk')->get();

        // Siapkan data yang akan diexport
        $result = [];
        foreach ($data as $trx) {
            foreach ($trx->transaksiItems as $item) {
                $modal = $item->stokProduk->harga ?? 0;
                $profit_item = $item -> keuntungan;
                $untung = $profit_item * $item->jumlah;
                $hargaJual = $modal + $item->keuntungan;

                $result[] = [
                    $trx->created_at->format('Y-m-d'),
                    $trx->kode_transaksi,
                    $item->stokProduk->produk->nama ?? '-',
                    $item->jumlah,
                    $hargaJual,
                    $modal,
                    $hargaJual * $item->jumlah,
                    $untung,
                    $trx->metode_pembayaran ?? '-',
                ];
            }
        }

        return collect($result);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Transaksi',
            'Produk',
            'Qty',
            'Harga Jual',
            'Harga Modal',
            'Subtotal',
            'Keuntungan',
            'Metode',
        ];
    }
}
