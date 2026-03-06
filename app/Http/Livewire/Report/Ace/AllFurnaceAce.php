<?php

namespace App\Http\Livewire\Report\Ace;

use App\Enums\EnumTypeMat;
use App\Exports\Ace\FullFurnace\AceAllFurnaceExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Report\AceReportService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class AllFurnaceAce extends BaseLivewireComponent
{
    public $startDate = null;
    public $endDate = null;
    public $activeTab = 'raw-material';
    public $hasSearched = false;
    public $shift;

    public $data = [];
    public $dateRange = [];

    public function mount()
    {
        $this->mountBase();
    }

    public function updatedActiveTab()
    {
        if ($this->hasSearched) {
            $this->search();
        }
    }

    #[On('Shift')]
    public function setShift($data)
    {
        $this->shift = $data;
    }

    #[On('Date')]
    public function Date($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->hasSearched = false;
        $this->data = [];
        $this->dateRange = [];
    }

    public function search()
    {

        if (!$this->startDate || !$this->endDate) {
            return;
        }
        // dd($this->startDate, $this->endDate, $this->shift);

        try {
            $start = Carbon::createFromFormat('d/m/Y', $this->startDate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d/m/Y', $this->endDate)->format('Y-m-d');

            $period = CarbonPeriod::create($start, $end);
            $this->dateRange = collect($period)->map(fn($date) => $date->format('Y-m-d'))->toArray();

            $type = ($this->activeTab === 'raw-material') ? EnumTypeMat::RawMaterial->value : EnumTypeMat::Additive->value;

            $this->data = app(AceReportService::class)->reportTotalFurnace($this->startDate, $this->endDate, $type, $this->shift);
            $this->hasSearched = true;
        } catch (\Exception $e) {
            $this->hasSearched = false;
            Log::error('ACE all furnace search failed', [
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'shift' => $this->shift,
                'activeTab' => $this->activeTab,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function export()
    {
        if (!$this->hasSearched) {
            return;
        }

        $fileName = 'ACE_All_Furnace_Report_' . now()->format('Ymd_His') . '.xlsx';
        $service = app(AceReportService::class);

        return Excel::download(new AceAllFurnaceExport($service, $this->startDate, $this->endDate, $this->shift, $this->dateRange), $fileName);
    }

    public function render()
    {
        return view('livewire.report.Ace.all-furnace-ace');
    }
}
