<?php

namespace App\Repositories\Report\Ace;

interface AceReportRepositoryInterface
{
    public function reportTotalFurnace($startDate, $endDate, $type, $shift);
    public function reportFurnace($startDate, $endDate, $type, $shift, $furnace);
    public function getKwhData($startDate, $endDate, $shift, $furnace);
    public function getTappingData($startDate, $endDate, $shift, $furnace);
    public function reportFurnaceWithKwhTapping($startDate, $endDate, $type, $shift, $furnace);
}
