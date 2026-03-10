<?php

namespace App\Exports\Jsh\MaterialAdjust;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JshMaterialAdjustExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return collect($this->rows)->map(function ($row) {
            return [
                'transaction_date' => $row['transaction_date'] ?? null,
                'material_id' => $row['material_id'] ?? null,
                'material_name' => $row['material_name'] ?? null,
                'material_type' => $row['material_type'] ?? null,
                'usage_qty_kg' => $row['usage_qty'] ?? 0,
                'adjust_qty_kg' => $row['adjust_qty'] ?? 0,
                'final_qty_kg' => $row['final_qty'] ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Transaction Date',
            'Material ID',
            'Material Name',
            'Material Type',
            'Usage Qty (kg)',
            'Adjust Qty (kg)',
            'Final Qty (kg)',
        ];
    }
}
