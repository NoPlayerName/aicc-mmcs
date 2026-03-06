<?php

namespace App\Repositories\Report\Ace;

interface AceReportRepositoryInterface
{
    public function reportTotalFurnace($startDate, $endDate, $type, $shift);
}
