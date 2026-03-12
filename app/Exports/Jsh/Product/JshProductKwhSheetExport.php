<?php

namespace App\Exports\Jsh\Product;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JshProductKwhSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $shift;
    protected $product;

    public function __construct($data, $shift, $product)
    {
        $this->data = $data;
        $this->shift = $shift;
        $this->product = $product;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function title(): string
    {
        return 'KWH';
    }

    public function headings(): array
    {
        $productName = collect($this->data)->first()['product_name'] ?? '-';

        return [
            ['Shift: ' . ($this->shift ?: 'D, S & N')],
            ['Product: ' . $productName],
            [],
            [
                'Date',
                'Charging',
                'Lot',
                'Furnace',
                'Charge Time',
                'KWH Start Charge',
                'KWH Ok Charge',
                'Power',
            ],
        ];
    }

    public function map($row): array
    {
        return [
            \Carbon\Carbon::parse($row['date'])->format('d/m/Y'),
            $row['charging'] ?? '-',
            $row['lot'] ?? '-',
            $row['plan_furnace'] ?? '-',
            $row['charge_time'] ?? '-',
            $row['kwh_start_charge'] ?? 0,
            $row['kwh_ok_charge'] ?? 0,
            $row['power'] ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:H2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A4:H4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A5:H' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        return [];
    }
}
