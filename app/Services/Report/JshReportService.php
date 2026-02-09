<?php

namespace App\Services\Report;

use App\Repositories\Report\Jsh\JshReportRepositoryInterface;
use Carbon\Carbon;

class JshReportService
{
    protected $report;
    public function __construct(JshReportRepositoryInterface $report)
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
    public function reportFurnace($startDate, $endDate, $type, $shift, $furnace)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->reportFurnace($newStartDate, $newEndDate, $type, $shift, $furnace);
        return $data;
    }
}
