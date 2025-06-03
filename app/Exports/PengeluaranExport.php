<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengeluaranExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
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
        $query = Pengeluaran::with('stokProduk.produk')
            ->whereBetween('created_at', [$this->dateParams['start_date'], $this->dateParams['end_date']])
            ->latest();

        $data = $query->get();

        return $data->map(function ($item) {
            return [
                'Tanggal' => $item->created_at->format('Y-m-d H:i:s'),
                'Produk' => $item->stokProduk->produk->nama ?? $item->nama_item ?? '-',
                'Jumlah Ditambah' => $item->jumlah_tambah,
                'Harga Satuan' => $item->stokProduk->harga ?? ($item->total / $item->jumlah_tambah),
                'Total' => $item->total ?? 0,
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

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->applyFromArray([
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
        $sheet->getStyle('A1:E' . $lastRow)->applyFromArray([
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
            'D' => '"Rp"#,##0', // Harga satuan
            'E' => '"Rp"#,##0', // Total
        ];
    }
}
