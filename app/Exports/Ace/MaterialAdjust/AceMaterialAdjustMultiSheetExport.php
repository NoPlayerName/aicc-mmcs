<?php

namespace App\Exports\Ace\MaterialAdjust;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AceMaterialAdjustMultiSheetExport implements WithMultipleSheets
{
    protected array $rows;
    protected array $dateRange;

    public function __construct(array $rows, array $dateRange)
    {
        $this->rows = $rows;
        $this->dateRange = $dateRange;
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
            new AceMaterialAdjustSheetExport($rawRows, $this->dateRange, 'Raw Material'),
            new AceMaterialAdjustSheetExport($additiveRows, $this->dateRange, 'Additive'),
        ];
    }
}
