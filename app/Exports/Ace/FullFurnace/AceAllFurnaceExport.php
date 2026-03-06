<?php

namespace App\Exports\Ace\FullFurnace;

use App\Enums\EnumTypeMat;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AceAllFurnaceExport implements WithMultipleSheets
{
    protected $repo, $start, $end, $shift, $dateRange;

    public function __construct($repo, $start, $end, $shift, $dateRange)
    {
        $this->repo = $repo;
        $this->start = $start;
        $this->end = $end;
        $this->shift = $shift;
        $this->dateRange = $dateRange;
    }

    public function sheets(): array
    {
        return [
            new AceAllFurnaceSheetExport(
                $this->repo->reportTotalFurnace($this->start, $this->end, EnumTypeMat::RawMaterial->value, $this->shift),
                $this->dateRange,
                'Raw Material',
                $this->shift,
                EnumTypeMat::RawMaterial->value,
            ),
            new AceAllFurnaceSheetExport(
                $this->repo->reportTotalFurnace($this->start, $this->end, EnumTypeMat::Additive->value, $this->shift),
                $this->dateRange,
                'Additive',
                $this->shift,
                EnumTypeMat::Additive->value,
            ),
        ];
    }
}
