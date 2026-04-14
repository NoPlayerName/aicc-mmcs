<?php

namespace App\Repositories\Report\Jsh;

interface JshReportRepositoryInterface
{
    public function reportTotalFurnace($startDate, $endDate, $type, $shift);
    public function reportFurnace($startDate, $endDate, $type, $shift, $furnace);
    public function getKwhData($startDate, $endDate, $shift, $furnace);
    public function getTappingData($startDate, $endDate, $shift, $furnace);
    public function reportFurnaceWithKwhTapping($startDate, $endDate, $type, $shift, $furnace);
    public function reportProduct($startDate, $endDate, $type, $shift, $product, $furnace = null);
    public function getKwhDataByProduct($startDate, $endDate, $shift, $product, $furnace = null);
    public function getTappingDataByProduct($startDate, $endDate, $shift, $product, $furnace = null);
    public function reportProductWithKwhTapping($startDate, $endDate, $type, $shift, $product, $furnace = null);
}
