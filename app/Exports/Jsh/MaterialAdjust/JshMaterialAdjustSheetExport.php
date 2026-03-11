<?php

namespace App\Exports\Jsh\MaterialAdjust;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JshMaterialAdjustSheetExport implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $rows;
    protected array $dateRange;
    protected string $title;

    public function __construct(array $rows, array $dateRange, string $title)
    {
        $this->rows = $rows;
        $this->dateRange = $dateRange;
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function array(): array
    {
        // Build pivot grouped by material
        $grouped = [];
        foreach ($this->rows as $row) {
            $key = ($row['material_id'] ?? '-') . '|' . ($row['material_name'] ?? '-');
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'material_name' => ($row['material_name'] ?? '-') . ' (' . ($row['material_id'] ?? '-') . ')',
                    'dates'          => [],
                    'subtotal_adjust' => 0,
                    'subtotal_final'  => 0,
                ];
            }
            $dateKey = $row['transaction_date'] ?? null;
            if ($dateKey) {
                $adj   = (float) ($row['adjust_qty'] ?? 0);
                $final = (float) ($row['final_qty']  ?? 0);
                if (!isset($grouped[$key]['dates'][$dateKey])) {
                    $grouped[$key]['dates'][$dateKey] = ['adjust' => 0, 'final' => 0];
                }
                $grouped[$key]['dates'][$dateKey]['adjust'] += $adj;
                $grouped[$key]['dates'][$dateKey]['final']  += $final;
                $grouped[$key]['subtotal_adjust'] += $adj;
                $grouped[$key]['subtotal_final']  += $final;
            }
        }

        // Header row 1: Material Name | date (placeholder for merge)... | Subtotal Adjust | Subtotal Final
        $header1 = ['Material Name'];
        $header2 = [''];
        foreach ($this->dateRange as $date) {
            $header1[] = Carbon::parse($date)->format('d/m/Y');
            $header1[] = ''; // will be merged
            $header2[] = 'ADJ';
            $header2[] = 'FINAL';
        }
        $header1[] = 'Subtotal Adjust';
        $header1[] = 'Subtotal Final';
        $header2[] = '';
        $header2[] = '';

        $data = [$header1, $header2];

        foreach ($grouped as $row) {
            $r = [$row['material_name']];
            foreach ($this->dateRange as $date) {
                $adj   = $row['dates'][$date]['adjust'] ?? 0;
                $final = $row['dates'][$date]['final']  ?? 0;
                $r[]   = $adj   != 0 ? $adj   : '';
                $r[]   = $final != 0 ? $final : '';
            }
            $r[] = $row['subtotal_adjust'] != 0 ? $row['subtotal_adjust'] : '';
            $r[] = $row['subtotal_final']  != 0 ? $row['subtotal_final']  : '';
            $data[] = $r;
        }

        if (count($data) === 2) {
            $empty = ['Belum ada data.'];
            for ($i = 1; $i < count($header1); $i++) {
                $empty[] = '';
            }
            $data[] = $empty;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();
        $colIdx  = 2;

        // Merge A1:A2 (Material Name spans 2 header rows)
        $sheet->mergeCells('A1:A2');

        // Merge date pairs in row 1 (each date spans ADJ + FINAL columns)
        foreach ($this->dateRange as $date) {
            $startCol = Coordinate::stringFromColumnIndex($colIdx);
            $endCol   = Coordinate::stringFromColumnIndex($colIdx + 1);
            $sheet->mergeCells("{$startCol}1:{$endCol}1");
            $colIdx += 2;
        }

        // Merge Subtotal Adjust and Subtotal Final in row 1+2
        $subtotalAdjCol   = Coordinate::stringFromColumnIndex($colIdx);
        $subtotalFinalCol = Coordinate::stringFromColumnIndex($colIdx + 1);
        $sheet->mergeCells("{$subtotalAdjCol}1:{$subtotalAdjCol}2");
        $sheet->mergeCells("{$subtotalFinalCol}1:{$subtotalFinalCol}2");

        // Header styling (bold, centered)
        $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
            'font'      => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // Borders on all cells
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Left-align material name column
        $sheet->getStyle("A3:A{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Row heights for headers
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(18);

        return [];
    }
}
