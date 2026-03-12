<?php

namespace App\Exports\Ace\Furnace;

use App\Enums\EnumTypeMat;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AceFurnaceExport implements WithMultipleSheets
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
            new AceFurnaceSheetExport(
                $this->repo->reportFurnace($this->start, $this->end, EnumTypeMat::RawMaterial->value, $this->shift, $this->furnace),
                $this->dateRange,
                'Raw Material',
                $this->shift,
                EnumTypeMat::RawMaterial->value,
                $this->furnace
            ),
            new AceFurnaceSheetExport(
                $this->repo->reportFurnace($this->start, $this->end, EnumTypeMat::Additive->value, $this->shift, $this->furnace),
                $this->dateRange,
                'Additive',
                $this->shift,
                EnumTypeMat::Additive->value,
                $this->furnace,
            ),
            new AceKwhSheetExport(
                $this->repo->getKwhData($this->start, $this->end, $this->shift, $this->furnace),
                $this->shift,
                $this->furnace,
            ),
            new AceTappingSheetExport(
                $this->repo->getTappingData($this->start, $this->end, $this->shift, $this->furnace),
                $this->shift,
                $this->furnace,
            ),
        ];
    }
}
