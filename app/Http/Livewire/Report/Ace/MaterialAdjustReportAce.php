<?php

namespace App\Http\Livewire\Report\Ace;

use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Master\Material\MaterialService;
use Livewire\Attributes\Title;

class MaterialAdjustReportAce extends BaseLivewireComponent
{
    public $startDate = null;
    public $endDate = null;
    public $material = '';
    public $materials = [];
    public $rows = [];
    public $hasSearched = false;

    public function mount()
    {
        $this->mountBase();

        $raw = collect(app(MaterialService::class)->getRawMat())->map(function ($item) {
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

    public function search()
    {
        $this->hasSearched = true;
        $this->rows = [];
    }

    public function export()
    {
        if (!$this->hasSearched) {
            return;
        }

        $this->dispatch('success', message: 'UI export ACE siap. Backend export belum diaktifkan.');
    }

    #[Title('ACE Material Adjust Report')]
    public function render()
    {
        return view('livewire.report.Ace.material-adjust-report-ace');
    }
}
