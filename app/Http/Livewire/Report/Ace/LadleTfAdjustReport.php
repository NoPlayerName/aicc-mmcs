<?php

namespace App\Http\Livewire\Report\Ace;

use App\Exports\Ace\LadleTransfer\AceLadleTfAdjustExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Master\Material\MaterialService;
use App\Services\MaterialUseAce\LadleTfAdjustService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

class LadleTfAdjustReport extends BaseLivewireComponent
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
        $this->materials = collect(app(MaterialService::class)->getInoculant())
            ->map(fn($item) => [
                'material_code' => $item['material_code'] ?? null,
                'material_name' => $item['material_name'] ?? '-',
            ])
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

        $this->rows = app(LadleTfAdjustService::class)
            ->getReport($start, $end, $this->material ?: null);

        $this->hasSearched = true;
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

        $rows = app(LadleTfAdjustService::class)
            ->getReport($start, $end, $this->material ?: null);

        $fileName = 'ACE_Ladle_TF_Adjust_Report_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new AceLadleTfAdjustExport($rows, $start, $end, $this->material ?: null),
            $fileName
        );
    }

    #[Title('ACE Ladle Transfer Adjust Report')]
    public function render()
    {
        return view('livewire.report.Ace.ladle-tf-adjust-report');
    }
}
