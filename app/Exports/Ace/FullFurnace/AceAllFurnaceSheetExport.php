<?php

namespace App\Exports\Ace\FullFurnace;

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

class AceAllFurnaceSheetExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $dateRange;
    protected $title;
    protected $shift;
    protected $type;

    public function __construct($data, $dateRange, $title, $shift, $type)
    {
        $this->data = $data;
        $this->dateRange = $dateRange;
        $this->title = $title;
        $this->shift = $shift;
        $this->type = $type;
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
        $row2 = ['Material Name'];
        $row3 = [''];

        foreach ($this->dateRange as $date) {
            $formattedDate = \Carbon\Carbon::parse($date)->format('d/m/Y');
            if ($isAdditive) {
                $row2[] = $formattedDate;
                $row2[] = '';
                $row3[] = 'P.ADJ';
                $row3[] = 'ADJ';
            } else {
                $row2[] = $formattedDate;
                $row3[] = '';
            }
        }

        $row2[] = 'Subtotal';

        return [
            $row1,
            [],
            $row2,
            $row3,
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

                $sheet->mergeCells("{$startCol}3:{$endCol}3");
                $columnIndex += 2;
            }
        }

        $styleArray = [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            'A3:' . $lastCol . ($isAdditive ? '4' : '3') => [
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

        $sheet->getStyle("A3:{$lastCol}{$lastRow}")->applyFromArray([
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
