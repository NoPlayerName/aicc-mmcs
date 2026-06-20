<?php

namespace App\Http\Livewire\Report\Jsh;

use App\Exports\Jsh\MaterialAdjust\JshMaterialAdjustMultiSheetExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Master\Material\MaterialService;
use App\Services\MaterialUseJsh\MaterialAdjustJshService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

class MaterialAdjustReportJsh extends BaseLivewireComponent
{
    public $startDate = null;
    public $endDate = null;
    public $material = '';
    public $activeTab = 'raw-material';
    public $materials = [];
    public $rows = [];
    public $dateRange = [];
    public $pivotRows = [];
    public $hasSearched = false;

    public function mount()
    {
        $this->mountBase();

        $raw = collect(app(MaterialService::class)->getRawMatJsh())->map(function ($item) {
            return [
                'material_code' => $item['material_code'] ?? null,
                'material_name' => $item['material_name'] ?? '-',
                'material_type' => 'RAW',
            ];
        });

        $additive = collect(app(MaterialService::class)->getAdditive())->map(function ($item) {
            return [
                'material_code' => $item['material_code'] ?? null,
                'material_name' => $item['material_name'] ?? '-',
                'material_type' => 'ADDITIVE',
            ];
        });

        $this->materials = $raw
            ->merge($additive)
            ->filter(fn($item) => !empty($item['material_code']))
            ->unique('material_code')
            ->sortBy('material_name')
            ->values()
            ->toArray();
    }

    #[On('Date')]
    public function setDateRange($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function updatedActiveTab()
    {
        if ($this->hasSearched) {
            $this->search();
        }
    }

    protected function normalizeDate(string $date): string
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    public function search()
    {
        if (empty($this->startDate) || empty($this->endDate)) {
            $this->dispatch('error', message: 'Tanggal mulai dan akhir wajib diisi.');
            return;
        }

        try {
            $start = $this->normalizeDate($this->startDate);
            $end = $this->normalizeDate($this->endDate);
        } catch (\Throwable $th) {
            $this->dispatch('error', message: 'Format tanggal tidak valid.');
            return;
        }

        $this->hasSearched = true;
        $rows = app(MaterialAdjustJshService::class)
            ->getReport($start, $end, $this->material ?: null);

        $targetType = $this->activeTab === 'raw-material' ? 'RAW' : 'ADDITIVE';
        $this->rows = collect($rows)
            ->where('material_type', $targetType)
            ->values()
            ->toArray();

        $period = CarbonPeriod::create($start, $end);
        $this->dateRange = [];
        foreach ($period as $date) {
            $this->dateRange[] = $date->format('Y-m-d');
        }

        $grouped = [];
        foreach ($this->rows as $row) {
            $materialKey = ($row['material_id'] ?? '-') . '|' . ($row['material_name'] ?? '-');
            if (!isset($grouped[$materialKey])) {
                $grouped[$materialKey] = [
                    'material_id' => $row['material_id'] ?? '-',
                    'material_name' => $row['material_name'] ?? '-',
                    'material_type' => $row['material_type'] ?? '-',
                    'dates' => [],
                    'subtotal_adjust' => 0,
                    'subtotal_final' => 0,
                ];
            }

            $dateKey = $row['transaction_date'] ?? null;
            if ($dateKey) {
                $adjustQty = (float) ($row['adjust_qty'] ?? 0);
                $finalQty = (float) ($row['final_qty'] ?? 0);
                if (!isset($grouped[$materialKey]['dates'][$dateKey])) {
                    $grouped[$materialKey]['dates'][$dateKey] = [
                        'adjust' => 0,
                        'final' => 0,
                    ];
                }

                $grouped[$materialKey]['dates'][$dateKey]['adjust'] += $adjustQty;
                $grouped[$materialKey]['dates'][$dateKey]['final'] += $finalQty;
                $grouped[$materialKey]['subtotal_adjust'] += $adjustQty;
                $grouped[$materialKey]['subtotal_final'] += $finalQty;
            }
        }

        $this->pivotRows = collect($grouped)
            ->sortBy('material_name')
            ->values()
            ->toArray();
    }

    public function export()
    {
        if (!$this->hasSearched) {
            return;
        }

        if (empty($this->startDate) || empty($this->endDate)) {
            $this->dispatch('error', message: 'Tanggal mulai dan akhir wajib diisi.');
            return;
        }

        try {
            $start = $this->normalizeDate($this->startDate);
            $end = $this->normalizeDate($this->endDate);
        } catch (\Throwable $th) {
            $this->dispatch('error', message: 'Format tanggal tidak valid.');
            return;
        }

        $rows = app(MaterialAdjustJshService::class)
            ->getReport($start, $end, $this->material ?: null);

        // Build full date range for pivot columns
        $dateRange = $this->dateRange;
        if (empty($dateRange)) {
            $period = \Carbon\CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $dateRange[] = $date->format('Y-m-d');
            }
        }

        $fileName = 'JSH_Material_Adjust_Report_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new JshMaterialAdjustMultiSheetExport($rows, $dateRange), $fileName);
    }

    #[Title('JSH Material Adjust Report')]
    public function render()
    {
        return view('livewire.report.jsh.material-adjust-report-jsh');
    }
}
