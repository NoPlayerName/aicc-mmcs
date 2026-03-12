<?php

namespace App\Exports\Ace\LadleTransfer;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AceLadleTransferExport implements FromArray, ShouldAutoSize, WithStyles
{
    protected array $rows;
    protected string $startDate;
    protected string $endDate;
    protected ?string $shift;

    public function __construct(array $rows, string $startDate, string $endDate, ?string $shift = null)
    {
        $this->rows = $rows;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->shift = $shift;
    }

    public function array(): array
    {
        $data = [
            ['ACE Ladle Transfer Report'],
            ['Period', Carbon::parse($this->startDate)->format('d/m/Y') . ' - ' . Carbon::parse($this->endDate)->format('d/m/Y')],
            ['Shift', $this->shift ?: 'All Shift'],
            [],
            [
                'Date',
                'Shift',
                'Furnace',
                'Lot',
                'Product',
                'Molten Weight (kg)',
                'Ladle Temp',
                'Weighing',
                'Conveyor',
                'Ladle Drop',
                'Treatment',
                'Total Inoculant (kg)',
                'Inoculant Name',
                'Created By',
            ],
        ];

        foreach ($this->rows as $row) {
            $inoculantName = collect($row['inoculants'] ?? [])->pluck('material_name')->filter()->implode(', ');

            $data[] = [
                !empty($row['transaction_date']) ? Carbon::parse($row['transaction_date'])->format('d/m/Y') : '-',
                $row['shift'] ?? '-',
                $row['furnace'] ?? '-',
                $row['lot'] ?? '-',
                $row['product'] ?? '-',
                $this->formatNumber($row['molten_weight'] ?? 0),
                $this->formatNumber($row['ladle_molten_temp'] ?? 0),
                !empty($row['weighing_status']) ? 'OK' : 'NO',
                !empty($row['conveyor_drop_status']) ? 'OK' : 'NO',
                !empty($row['ladle_drop_status']) ? 'OK' : 'NO',
                !empty($row['treatment_duration_check']) ? 'OK' : 'NO',
                $this->formatNumber($row['total_inoculant_weight'] ?? 0),
                $inoculantName ?: '-',
                $row['created_by'] ?? '-',
            ];
        }

        if (count($data) === 5) {
            $data[] = ['Belum ada data.'];
        }

        return $data;
    }

    protected function formatNumber($value): string
    {
        return rtrim(rtrim(number_format((float) $value, 3, '.', ''), '0'), '.');
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->mergeCells("A1:{$lastCol}1");

        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ]);

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("A5:{$lastCol}5")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        $sheet->getStyle("A6:{$lastCol}{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle("F6:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("L6:L{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }
}
