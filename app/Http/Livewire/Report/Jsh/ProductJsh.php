<?php

namespace App\Http\Livewire\Report\Jsh;


use App\Enums\EnumTypeMat;
use App\Exports\Jsh\Product\JshProductExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Master\ProductJsh\ModelService;
use App\Services\Report\JshReportService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class ProductJsh extends BaseLivewireComponent
{

    public $startDate = null;
    public $endDate = null;
    public $activeTab = 'raw-material';
    public $hasSearched = false;
    public $shift;

    public $productSelect;
    public $product;

    public $data = [];
    public $kwhData = [];
    public $tappingData = [];
    public $dateRange = [];

    public function mount()
    {
        $this->mountBase();
    }
    public function loadProduct()
    {
        $this->productSelect = app(ModelService::class)->getModel();
    }


    public function updatedActiveTab()
    {
        if ($this->hasSearched) {
            $this->search();
        }
    }

    #[On('Product')]
    public function product($data)
    {
        $this->product = $data;
    }

    #[On('Shift')]
    public function shift($data)
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
        $this->kwhData = [];
        $this->tappingData = [];
        $this->dateRange = [];
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

            $type = ($this->activeTab === 'additive') ? EnumTypeMat::Additive->value : EnumTypeMat::RawMaterial->value;

            $reportData = app(JshReportService::class)->reportProductWithKwhTapping(
                $this->startDate,
                $this->endDate,
                $type,
                $this->shift,
                $this->product
            );

            $this->data = $reportData['materials'] ?? collect();
            $this->kwhData = $reportData['kwh'] ?? [];
            $this->tappingData = $reportData['tapping'] ?? [];
            $this->hasSearched = true;
        } catch (\Exception $e) {
            $this->hasSearched = false;
        }
    }

    public function export()
    {
        if (!$this->hasSearched) {
            return;
        }

        $fileName = 'JSH_Product_Report_' . now()->format('Ymd_His') . '.xlsx';
        $service = app(JshReportService::class);
        return Excel::download(new JshProductExport($service, $this->startDate, $this->endDate, $this->shift, $this->dateRange, $this->product), $fileName);
    }

    public function render()
    {
        $this->loadProduct();
        return view('livewire.report.jsh.product-jsh');
    }
}
