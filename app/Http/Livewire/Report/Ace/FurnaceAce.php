<?php

namespace App\Http\Livewire\Report\Ace;

use App\Enums\EnumFurnaceAce;
use App\Enums\EnumTypeMat;
use App\Exports\Ace\Furnace\AceFurnaceExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Report\AceReportService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class FurnaceAce extends BaseLivewireComponent
{
    public $startDate = null;
    public $endDate = null;
    public $activeTab = 'raw-material';
    public $hasSearched = false;
    public $shift;

    public $furnaceSelect;
    public $furnace;

    public $data = [];
    public $dateRange = [];
    public $kwhData = [];
    public $tappingData = [];

    public function mount()
    {
        $this->mountBase();
        $this->furnaceSelect = EnumFurnaceAce::all();
    }

    public function updatedActiveTab()
    {
        if ($this->hasSearched) {
            $this->search();
        }
    }

    #[On('Furnace')]
    public function setFurnace($data)
    {
        $this->furnace = $data;
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
        $this->kwhData = [];
        $this->tappingData = [];
    }

    public function search()
    {
        if (!$this->startDate || !$this->endDate) {
            return;
        }

        try {
            $start = Carbon::createFromFormat('d/m/Y', $this->startDate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d/m/Y', $this->endDate)->format('Y-m-d');

            $period = CarbonPeriod::create($start, $end);
            $this->dateRange = collect($period)->map(fn($date) => $date->format('Y-m-d'))->toArray();

            $type = ($this->activeTab === 'raw-material') ? EnumTypeMat::RawMaterial->value : EnumTypeMat::Additive->value;

            $this->data = app(AceReportService::class)->reportFurnace($this->startDate, $this->endDate, $type, $this->shift, $this->furnace);
            $reportData = app(AceReportService::class)->reportFurnaceWithKwhTapping($this->startDate, $this->endDate, $type, $this->shift, $this->furnace);

            $this->kwhData = $reportData['kwh'] ?? [];
            $this->tappingData = $reportData['tapping'] ?? [];

            $this->hasSearched = true;
        } catch (\Exception $e) {
            $this->hasSearched = false;
            Log::error('ACE furnace report search failed', [
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'shift' => $this->shift,
                'furnace' => $this->furnace,
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

        $fileName = 'ACE_Furnace_Report_' . now()->format('Ymd_His') . '.xlsx';
        $service = app(AceReportService::class);
        return Excel::download(new AceFurnaceExport($service, $this->startDate, $this->endDate, $this->shift, $this->dateRange, $this->furnace), $fileName);
    }

    public function render()
    {
        return view('livewire.report.Ace.furnace-ace');
    }
}
