<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemasukanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Transaksi::with('transaksiItems.stokProduk.produk');

        if ($this->filter === 'harian') {
            $query->whereDate('created_at', now());
        } elseif ($this->filter === 'mingguan') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filter === 'bulanan') {
            $query->whereMonth('created_at', now()->month);
        }

        $data = $query->get();

        $result = [];
        foreach ($data as $trx) {
            foreach ($trx->transaksiItems as $item) {
                $modal = $item->stokProduk->harga ?? 0;
                $profitItem = $item->keuntungan;
                $hargaJual = $modal + $profitItem;
                $untung = $profitItem * $item->jumlah;
                $subtotal = $hargaJual * $item->jumlah;

                $result[] = [
                    $trx->created_at->format('Y-m-d'),
                    $item->stokProduk->produk->nama ?? '-',
                    $item->jumlah,
                    $hargaJual,
                    $modal,
                    $subtotal,
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
            'Produk',
            'Qty',
            'Harga Jual',
            'Harga Modal',
            'Subtotal',
            'Keuntungan',
            'Metode',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4A90E2'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function columnFormats(): array
    {
        return [
            'E' => '"Rp"#,##0', // Harga Jual
            'F' => '"Rp"#,##0', // Harga Modal
            'G' => '"Rp"#,##0', // Subtotal
            'H' =>'"Rp"#,##0', // Keuntungan
        ];
    }
}
