<?php

namespace App\Exports\Ace\Furnace;

use App\Enums\EnumTypeMat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AceFurnaceSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $dateRange;
    protected $title;
    protected $shift;
    protected $type;
    protected $furnace;

    public function __construct($data, $dateRange, $title, $shift, $type, $furnace)
    {
        $this->data = $data;
        $this->dateRange = $dateRange;
        $this->title = $title;
        $this->shift = $shift;
        $this->type = $type;
        $this->furnace = $furnace;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        $isAdditive = ($this->type === EnumTypeMat::Additive->value);

        $row1 = ['Shift: ' . ($this->shift ?: 'D, S & N')];
        $row2 = ['Furnace: ' . ($this->furnace ?: '-')];
        $row3 = ['Material Name'];
        $row4 = [''];

        foreach ($this->dateRange as $date) {
            $formattedDate = \Carbon\Carbon::parse($date)->format('d/m/Y');
            if ($isAdditive) {
                $row3[] = $formattedDate;
                $row3[] = '';
                $row4[] = 'P.ADJ';
                $row4[] = 'ADJ';
            } else {
                $row3[] = $formattedDate;
                $row4[] = '';
            }
        }

        $row3[] = 'Subtotal';

        return [
            $row1,
            $row2,
            [],
            $row3,
            $row4,
        ];
    }

    public function map($row): array
    {
        $isAdditive = ($this->type === EnumTypeMat::Additive->value);
        $mapped = [
            $row->material_name,
        ];

        foreach ($this->dateRange as $date) {
            $dateKey = str_replace('-', '_', $date);
            if ($isAdditive) {
                $preAlias = 'pre_date_' . $dateKey;
                $adjAlias = 'date_' . $dateKey;

                $mapped[] = $row->$preAlias ?? 0;
                $mapped[] = $row->$adjAlias ?? 0;
            } else {
                $alias = 'date_' . $dateKey;
                $mapped[] = $row->$alias ?? 0;
            }
        }

        $mapped[] = $row->subtotal;
        return $mapped;
    }

    public function styles(Worksheet $sheet)
    {
        $isAdditive = ($this->type === EnumTypeMat::Additive->value);
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        if ($isAdditive) {
            $columnIndex = 2;
            foreach ($this->dateRange as $date) {
                $startCol = Coordinate::stringFromColumnIndex($columnIndex);
                $endCol = Coordinate::stringFromColumnIndex($columnIndex + 1);

                $sheet->mergeCells("{$startCol}4:{$endCol}4");
                $columnIndex += 2;
            }
        }

        $styleArray = [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            'A4:' . $lastCol . ($isAdditive ? '5' : '4') => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                ],
            ],
        ];

        $sheet->getStyle("A4:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return $styleArray;
    }
}
