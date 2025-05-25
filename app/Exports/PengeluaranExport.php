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

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Pengeluaran::with('stokProduk.produk');

        if ($this->filter === 'harian') {
            $query->whereDate('created_at', now());
        } elseif ($this->filter === 'mingguan') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filter === 'bulanan') {
            $query->whereMonth('created_at', now()->month);
        }

        $data = $query->get();

        return $data->map(function ($item) {
            return [
                'Tanggal' => $item->created_at->format('Y-m-d'),
                'Produk' => $item->stokProduk->produk->nama ?? '-',
                'Jumlah Ditambah' => $item->jumlah_tambah,
                'Harga Satuan' => $item->stokProduk->harga ?? 0,
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
