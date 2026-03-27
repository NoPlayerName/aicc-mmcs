<?php

namespace App\Exports\Ace\LadleTransfer;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AceLadleTfAdjustExport implements FromArray, ShouldAutoSize, WithStyles
{
    protected array $rows;
    protected string $startDate;
    protected string $endDate;
    protected ?string $material;

    public function __construct(array $rows, string $startDate, string $endDate, ?string $material = null)
    {
        $this->rows = $rows;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->material = $material;
    }

    public function array(): array
    {
        $data = [
            ['ACE Ladle Transfer Adjust Report'],
            ['Period', Carbon::parse($this->startDate)->format('d/m/Y') . ' - ' . Carbon::parse($this->endDate)->format('d/m/Y')],
            ['Material', $this->material ?: 'All Inoculant'],
            [],
            [
                'Transaction Date',
                'Material ID',
                'Material Name',
                'Qty Adjust (kg)',
                'Note',
                'Created By',
                'Created At',
            ],
        ];

        foreach ($this->rows as $row) {
            $data[] = [
                !empty($row['transaction_date']) ? Carbon::parse($row['transaction_date'])->format('d/m/Y') : '-',
                $row['material_id'] ?? '-',
                $row['material_name'] ?? '-',
                $this->formatNumber($row['qty_adjust'] ?? 0),
                $row['note'] ?? '-',
                $row['created_by'] ?? '-',
                !empty($row['created_at']) ? Carbon::parse($row['created_at'])->format('d/m/Y H:i:s') : '-',
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

        $sheet->getStyle("D6:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A6:{$lastCol}{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        return [];
    }
}
