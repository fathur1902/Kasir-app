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
    protected $dateParams;

    public function __construct($filter, $dateParams)
    {
        $this->filter = $filter;
        $this->dateParams = $dateParams;
    }

    public function collection()
    {
        $query = Transaksi::with('transaksiItems.stokProduk.produk')
            ->whereBetween('created_at', [$this->dateParams['start_date'], $this->dateParams['end_date']])
            ->latest();

        $data = $query->get();

        $result = [];
        foreach ($data as $trx) {
            foreach ($trx->transaksiItems as $item) {
                $modal = $item->stokProduk->harga ?? 0;
                $profitItem = $item->keuntungan ?? 0;
                $hargaJual = $modal + $profitItem;
                $untung = $profitItem * $item->jumlah;
                $subtotal = $hargaJual * $item->jumlah;

                $result[] = [
                    $trx->created_at->format('Y-m-d H:i:s'),
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
        $sheet->getStyle('A1:H1')->applyFromArray([
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
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function columnFormats(): array
    {
        return [
            'D' => '"Rp"#,##0', // Harga Jual
            'E' => '"Rp"#,##0', // Harga Modal
            'F' => '"Rp"#,##0', // Subtotal
            'G' => '"Rp"#,##0', // Keuntungan
        ];
    }
}
