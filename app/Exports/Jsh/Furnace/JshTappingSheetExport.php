<?php

namespace App\Exports\Jsh\Furnace;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JshTappingSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $shift;
    protected $furnace;

    public function __construct($data, $shift, $furnace)
    {
        $this->data = $data;
        $this->shift = $shift;
        $this->furnace = $furnace;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function title(): string
    {
        return 'Temperature Tapping';
    }

    public function headings(): array
    {
        return [
            ['Shift: ' . ($this->shift ?: 'D & N')],
            ['Furnace: ' . ($this->furnace ?: '-')],
            [],
            [
                'Date',
                'Charging',
                'Lot',
                'Furnace',
                'Temperature',
                'Type Tapping',
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
            $row['temperatur'] ?? 0,
            $row['type_tapping'] ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Header styling
        $sheet->getStyle('A1:F2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            // 'fill' => [
            //     'fillType' => Fill::FILL_SOLID,
            //     'startColor' => ['rgb' => 'D3D3D3'],
            // ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Heading styling
        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true,  'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                // 'startColor' => ['rgb' => '366092'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Data borders
        $sheet->getStyle('A5:F' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        return [];
    }
}
