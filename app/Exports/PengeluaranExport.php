<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengeluaranExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pengeluaran::with('stokProduk.produk')->get()->map(function($item){
            return [
                'Tanggal' => $item->created_at->format('Y-m-d'),
                'Produk' => $item->stokProduk->produk->nama ?? '-',
                'Jumlah Ditambah' => $item->jumlah_tambah,
                'Harga Satuan' => $item->stokProduk->harga,
                'Total' => $item->total,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Produk',
            'Jumlah Ditambah',
            'Harga Satuan',
            'Total',
        ];
    }
}
