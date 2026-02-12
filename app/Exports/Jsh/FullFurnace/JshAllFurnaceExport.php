<?php

namespace App\Exports\Jsh\FullFurnace;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Enums\EnumTypeMat;

class  JshAllFurnaceExport implements WithMultipleSheets
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
            // Sheet 1: Raw Material
            new JshAllFurnaceSheetExport(
                $this->repo->reportTotalFurnace($this->start, $this->end, EnumTypeMat::RawMaterial->value, $this->shift),
                $this->dateRange,
                'Raw Material',
                $this->shift,
                EnumTypeMat::RawMaterial->value,
            ),
            // Sheet 2: Additive
            new JshAllFurnaceSheetExport(
                $this->repo->reportTotalFurnace($this->start, $this->end, EnumTypeMat::Additive->value, $this->shift),
                $this->dateRange,
                'Additive',
                $this->shift,
                EnumTypeMat::Additive->value,
            ),
        ];
    }
}
