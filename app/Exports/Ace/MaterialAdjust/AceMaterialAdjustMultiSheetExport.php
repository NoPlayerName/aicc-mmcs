<?php

namespace App\Exports\Ace\MaterialAdjust;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AceMaterialAdjustMultiSheetExport implements WithMultipleSheets
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
            new AceMaterialAdjustSheetExport($rawRows, 'Raw Material'),
            new AceMaterialAdjustSheetExport($additiveRows, 'Additive'),
        ];
    }
}
