<?php

namespace App\Exports\Ace\Product;

use App\Enums\EnumTypeMat;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AceProductExport implements WithMultipleSheets
{
    protected $repo, $start, $end, $shift, $dateRange, $product;

    public function __construct($repo, $start, $end, $shift, $dateRange, $product)
    {
        $this->repo = $repo;
        $this->start = $start;
        $this->end = $end;
        $this->shift = $shift;
        $this->dateRange = $dateRange;
        $this->product = $product;
    }

    public function sheets(): array
    {
        return [
            new AceProductSheetExport(
                $this->repo->reportProduct($this->start, $this->end, EnumTypeMat::RawMaterial->value, $this->shift, $this->product),
                $this->dateRange,
                'Raw Material',
                $this->shift,
                EnumTypeMat::RawMaterial->value,
            ),
            new AceProductSheetExport(
                $this->repo->reportProduct($this->start, $this->end, EnumTypeMat::Additive->value, $this->shift, $this->product),
                $this->dateRange,
                'Additive',
                $this->shift,
                EnumTypeMat::Additive->value,
            ),
            new AceProductKwhSheetExport(
                $this->repo->getKwhDataByProduct($this->start, $this->end, $this->shift, $this->product),
                $this->shift,
                $this->product
            ),
            new AceProductTappingSheetExport(
                $this->repo->getTappingDataByProduct($this->start, $this->end, $this->shift, $this->product),
                $this->shift,
                $this->product
            ),
        ];
    }
}
