<?php

namespace App\Exports\Jsh\MaterialAdjust;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class JshMaterialAdjustMultiSheetExport implements WithMultipleSheets
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function sheets(): array
    {
        $rawRows = collect($this->rows)
            ->where('material_type', 'RAW')
            ->values()
            ->toArray();

        $additiveRows = collect($this->rows)
            ->where('material_type', 'ADDITIVE')
            ->values()
            ->toArray();

        return [
            new JshMaterialAdjustSheetExport($rawRows, 'Raw Material'),
            new JshMaterialAdjustSheetExport($additiveRows, 'Additive'),
        ];
    }
}
