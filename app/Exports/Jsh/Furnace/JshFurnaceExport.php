<?php

namespace App\Exports\Jsh\Furnace;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Enums\EnumTypeMat;

class JshFurnaceExport implements WithMultipleSheets
{
    protected $repo, $start, $end, $shift, $dateRange, $furnace;

    public function __construct($repo, $start, $end, $shift, $dateRange, $furnace)
    {
        $this->repo = $repo;
        $this->start = $start;
        $this->end = $end;
        $this->shift = $shift;
        $this->dateRange = $dateRange;
        $this->furnace = $furnace;
    }

    public function sheets(): array
    {
        return [
            // Sheet 1: Raw Material
            new JshFurnaceSheetExport(
                $this->repo->reportFurnace($this->start, $this->end, EnumTypeMat::RawMaterial->value, $this->shift, $this->furnace),
                $this->dateRange,
                'Raw Material',
                $this->shift,
                EnumTypeMat::RawMaterial->value,
                $this->furnace
            ),
            // Sheet 2: Additive
            new JshFurnaceSheetExport(
                $this->repo->reportFurnace($this->start, $this->end, EnumTypeMat::Additive->value, $this->shift, $this->furnace),
                $this->dateRange,
                'Additive',
                $this->shift,
                EnumTypeMat::Additive->value,
                $this->furnace,
            ),
            // Sheet 3: KWH
            new JshKwhSheetExport(
                $this->repo->getKwhData($this->start, $this->end, $this->shift, $this->furnace),
                // $this->dateRange,
                $this->shift,
                $this->furnace,
            ),
            // Sheet 4: Temperature Tapping
            new JshTappingSheetExport(
                $this->repo->getTappingData($this->start, $this->end, $this->shift, $this->furnace),
                // $this->dateRange,
                $this->shift,
                $this->furnace,
            ),
        ];
    }
}
