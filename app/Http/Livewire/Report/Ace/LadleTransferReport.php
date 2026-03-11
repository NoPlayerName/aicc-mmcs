<?php

namespace App\Http\Livewire\Report\Ace;

use App\Exports\Ace\LadleTransfer\AceLadleTransferExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\MaterialUseAce\MaterialUseAceService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

class LadleTransferReport extends BaseLivewireComponent
{
    public $startDate = null;
    public $endDate = null;
    public $shift = '';
    public $rows = [];
    public $hasSearched = false;

    public function mount()
    {
        $this->mountBase();
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

        $this->rows = app(MaterialUseAceService::class)
            ->getLadleTransferReport($start, $end, $this->shift ?: null);

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

        $rows = app(MaterialUseAceService::class)
            ->getLadleTransferReport($start, $end, $this->shift ?: null);

        $fileName = 'ACE_Ladle_Transfer_Report_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new AceLadleTransferExport($rows, $start, $end, $this->shift ?: null),
            $fileName
        );
    }

    #[Title('ACE Ladle Transfer Report')]
    public function render()
    {
        return view('livewire.report.Ace.ladle-transfer-report');
    }
}
