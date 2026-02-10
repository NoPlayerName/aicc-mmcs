<?php

namespace App\Repositories\Report\Jsh;

interface JshReportRepositoryInterface
{
    public function reportTotalFurnace($startDate, $endDate, $type, $shift);
    public function reportFurnace($startDate, $endDate, $type, $shift, $furnace);
    public function reportProduct($startDate, $endDate, $type, $shift, $product);
}
