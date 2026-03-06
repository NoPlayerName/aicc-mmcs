<?php

namespace App\Http\Livewire\Report\Jsh;

use App\Enums\EnumFurnace;
use App\Enums\EnumTypeMat;
use App\Exports\Jsh\Furnace\JshFurnaceExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Report\JshReportService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class FurnaceJsh extends BaseLivewireComponent
{
    public $startDate = null;
    public $endDate = null;
    public $activeTab = 'raw-material';
    public $hasSearched = false;
    public $shift;

    public $furnaceSelect;
    public $furnace;

    // Properti untuk menyimpan hasil agar bisa dibaca di View
    public $data = [];
    public $dateRange = [];
    public $kwhData = [];
    public $tappingData = [];

    public function mount()
    {
        $this->mountBase();
        $this->furnaceSelect =  EnumFurnace::all();
    }


    public function updatedActiveTab()
    {
        if ($this->hasSearched) {
            $this->search();
        }
    }


    #[On('Furnace')]
    public function furnace($data)
    {
        $this->furnace =  $data;
    }
    #[On('Shift')]
    public function shift($data)
    {
        $this->shift =  $data;
    }

    #[On('Date')]
    public function Date($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        // Reset hasil saat tanggal diubah agar user harus menekan Search lagi
        $this->hasSearched = false;
        $this->data = [];
        $this->dateRange = [];
        $this->kwhData = [];
        $this->tappingData = [];
    }

    public function search()
    {
        // Validasi input
        if (!$this->startDate || !$this->endDate) return;

        try {
            // Konversi format untuk query
            $start = Carbon::createFromFormat('d/m/Y', $this->startDate)->format('Y-m-d');
            $end = Carbon::createFromFormat('d/m/Y', $this->endDate)->format('Y-m-d');

            // 1. Set Date Range untuk header tabel
            $period = CarbonPeriod::create($start, $end);
            $this->dateRange = collect($period)->map(fn($date) => $date->format('Y-m-d'))->toArray();

            // 2. Tentukan tipe material
            $type = ($this->activeTab === 'raw-material') ? EnumTypeMat::RawMaterial->value : EnumTypeMat::Additive->value;

            // 3. Ambil data material
            $materialData = app(JshReportService::class)->reportFurnace($this->startDate, $this->endDate, $type, $this->shift, $this->furnace);
            $this->data = ($materialData instanceof Collection) ? $materialData : collect();

            // 4. Ambil data KWH dan Temperature Tapping (always fetch regardless of activeTab)
            $reportData = app(JshReportService::class)->reportFurnaceWithKwhTapping($this->startDate, $this->endDate, $type, $this->shift, $this->furnace);
            // dd($this->data, $reportData);
            $this->kwhData = is_array($reportData) ? ($reportData['kwh'] ?? []) : [];
            $this->tappingData = is_array($reportData) ? ($reportData['tapping'] ?? []) : [];

            $this->hasSearched = true;
        } catch (\Exception $e) {
            $this->hasSearched = false;
            Log::error('Search error: ' . $e->getMessage());
        }
    }

    public function export()
    {
        if (!$this->hasSearched) return;
        $fileName = 'JSH_Furnace_Report_' . now()->format('Ymd_His') . '.xlsx';
        $service = app(JshReportService::class);
        return Excel::download(new JshFurnaceExport($service, $this->startDate, $this->endDate, $this->shift, $this->dateRange, $this->furnace), $fileName);
    }

    public function render()
    {
        return view('livewire.report.jsh.furnace-jsh');
    }
}
