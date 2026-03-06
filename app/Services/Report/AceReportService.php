<?php

namespace App\Services\Report;

use App\Repositories\Report\Ace\AceReportRepositoryInterface;
use Carbon\Carbon;

class AceReportService
{
    protected $report;

    public function __construct(AceReportRepositoryInterface $report)
    {
        $this->report = $report;
    }

    public function reportTotalFurnace($startDate, $endDate, $type, $shift)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->reportTotalFurnace($newStartDate, $newEndDate, $type, $shift);
        return $data;
    }
}
