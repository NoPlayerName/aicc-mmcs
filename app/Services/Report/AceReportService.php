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

    public function reportFurnace($startDate, $endDate, $type, $shift, $furnace)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->reportFurnace($newStartDate, $newEndDate, $type, $shift, $furnace);
        return $data;
    }

    public function getKwhData($startDate, $endDate, $shift, $furnace)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->getKwhData($newStartDate, $newEndDate, $shift, $furnace);
        return $data;
    }

    public function getTappingData($startDate, $endDate, $shift, $furnace)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->getTappingData($newStartDate, $newEndDate, $shift, $furnace);
        return $data;
    }

    public function reportFurnaceWithKwhTapping($startDate, $endDate, $type, $shift, $furnace)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->reportFurnaceWithKwhTapping($newStartDate, $newEndDate, $type, $shift, $furnace);
        return $data;
    }

    public function reportProduct($startDate, $endDate, $type, $shift, $product)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->reportProduct($newStartDate, $newEndDate, $type, $shift, $product);
        return $data;
    }

    public function getKwhDataByProduct($startDate, $endDate, $shift, $product)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->getKwhDataByProduct($newStartDate, $newEndDate, $shift, $product);
        return $data;
    }

    public function getTappingDataByProduct($startDate, $endDate, $shift, $product)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->getTappingDataByProduct($newStartDate, $newEndDate, $shift, $product);
        return $data;
    }

    public function reportProductWithKwhTapping($startDate, $endDate, $type, $shift, $product)
    {
        $newStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d');
        $newEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
        $data = $this->report->reportProductWithKwhTapping($newStartDate, $newEndDate, $type, $shift, $product);
        return $data;
    }
}
