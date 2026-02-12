<?php

namespace App\Exports\Jsh\Product;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Enums\EnumTypeMat;

class  JshProductExport implements WithMultipleSheets
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
            // Sheet 1: Raw Material
            new JshProductSheetExport(
                $this->repo->reportProduct($this->start, $this->end, EnumTypeMat::RawMaterial->value, $this->shift, $this->product),
                $this->dateRange,
                'Raw Material',
                $this->shift,
                EnumTypeMat::RawMaterial->value,
                // $this->product

            ),
            // Sheet 2: Additive
            new JshProductSheetExport(
                $this->repo->reportProduct($this->start, $this->end, EnumTypeMat::Additive->value, $this->shift, $this->product),
                $this->dateRange,
                'Additive',
                $this->shift,
                EnumTypeMat::Additive->value,
                // $this->product,
            ),
        ];
    }
}
