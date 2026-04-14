<?php

namespace App\Http\Livewire\Report\Jsh;


use App\Enums\EnumFurnace;
use App\Enums\EnumTypeMat;
use App\Exports\Jsh\Product\JshProductExport;
use App\Http\Livewire\BaseLivewireComponent;
use App\Services\Master\ProductJsh\ModelService;
use App\Services\Report\JshReportService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductJsh extends BaseLivewireComponent
{

    public $startDate = null;
    public $endDate = null;
    public $activeTab = 'raw-material';
    public $hasSearched = false;
    public $shift;
    public $furnace;

    public $furnaceSelect;

    public $productSelect;
    public $product;

    public $data = [];
    public $kwhData = [];
    public $tappingData = [];
    public $dateRange = [];

    public function mount()
    {
        $this->mountBase();
        $this->furnaceSelect = EnumFurnace::all();
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

    #[On('Furnace')]
    public function furnace($data)
    {
        $this->furnace = $data;
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
                $this->product,
                $this->furnace
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

        $service = app(JshReportService::class);
        $templatePath = storage_path('app/templates/jsh/melting_check_sheet.xlsx');
        if (!File::exists($templatePath)) {
            $fileName = 'JSH_Product_Report_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new JshProductExport($service, $this->startDate, $this->endDate, $this->shift, $this->dateRange, $this->product, $this->furnace), $fileName);
        }

        $rawData = $service->reportProduct($this->startDate, $this->endDate, EnumTypeMat::RawMaterial->value, $this->shift, $this->product, $this->furnace);
        $additiveData = $service->reportProduct($this->startDate, $this->endDate, EnumTypeMat::Additive->value, $this->shift, $this->product, $this->furnace);
        $lotCountsByDay = $this->buildLotCountsByDay(collect($rawData), $this->dateRange, false);
        $lotCountsByDayAdditive = $this->buildLotCountsByDay(collect($additiveData), $this->dateRange, true);

        foreach ($lotCountsByDayAdditive as $day => $count) {
            $lotCountsByDay[$day] = max(($lotCountsByDay[$day] ?? 0), $count);
        }

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $productName = collect($rawData)->first()->product_name
            ?? collect($additiveData)->first()->product_name
            ?? '-';

        $sheet->setCellValue('A6', 'FURNACE NO : ' . ($this->furnace ?: 'ALL'));
        $sheet->setCellValue('X2', $productName);
        $sheet->setCellValue('R3', $this->shift ?: 'ALL');
        $sheet->setCellValue('D2', "Check Sheet\nMELTING\nMATRIAL DAILY " . $productName);
        $sheet->setCellValue('B10', $productName);
        $sheet->setCellValue('B11', $productName);

        for ($day = 1; $day <= 31; $day++) {
            $col = Coordinate::stringFromColumnIndex(3 + $day);
            $count = (int) ($lotCountsByDay[$day] ?? 0);
            $sheet->setCellValue($col . '10', $count > 0 ? $count : '');
        }

        $sheet->setCellValue('AI10', '=SUM(D10:AH10)');

        $rawAggregate = $this->buildAggregateByMaterialAndDay(collect($rawData), $this->dateRange, false);
        $additiveAggregate = $this->buildAggregateByMaterialAndDay(collect($additiveData), $this->dateRange, true);

        $this->fillSectionByTemplateRows($sheet, 14, 30, $rawAggregate['days'], $rawAggregate['labels']);
        $this->fillSectionByTemplateRows($sheet, 32, 45, $additiveAggregate['days'], $additiveAggregate['labels']);
        $sheet->setCellValue('AI46', '=SUM(AI14:AI45)');

        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $fileName = 'JSH_Product_Report_' . now()->format('Ymd_His') . '.xlsx';
        $outputPath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

        $writer = new Xlsx($spreadsheet);
        $writer->save($outputPath);

        return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
    }

    private function fillSectionByTemplateRows($sheet, int $startRow, int $endRow, array $aggregate, array $labels): void
    {
        $rowMaterialMap = [];
        for ($row = $startRow; $row <= $endRow; $row++) {
            $materialName = trim((string) $sheet->getCell('B' . $row)->getValue());
            $rowMaterialMap[$row] = $this->normalizeMaterialName($materialName);
        }

        $aggregateKeys = array_keys($aggregate);
        $usedKeys = [];
        $resolvedMap = [];

        // First pass: exact key match
        foreach ($rowMaterialMap as $row => $templateKey) {
            if ($templateKey !== '' && isset($aggregate[$templateKey])) {
                $resolvedMap[$row] = $templateKey;
                $usedKeys[$templateKey] = true;
            }
        }

        // Second pass: fuzzy key match by similarity for unmatched rows
        foreach ($rowMaterialMap as $row => $templateKey) {
            if (isset($resolvedMap[$row]) || $templateKey === '') {
                continue;
            }

            $bestKey = null;
            $bestScore = 0.0;

            foreach ($aggregateKeys as $candidateKey) {
                if (isset($usedKeys[$candidateKey])) {
                    continue;
                }

                similar_text($templateKey, $candidateKey, $percent);

                if (str_contains($templateKey, $candidateKey) || str_contains($candidateKey, $templateKey)) {
                    $percent += 15;
                }

                if ($percent > $bestScore) {
                    $bestScore = $percent;
                    $bestKey = $candidateKey;
                }
            }

            if ($bestKey !== null && $bestScore >= 50) {
                $resolvedMap[$row] = $bestKey;
                $usedKeys[$bestKey] = true;
            }
        }

        // Third pass: fallback by highest subtotal for any still-unmatched row
        $remainingEntries = [];
        foreach ($aggregate as $key => $dayMap) {
            if (!isset($usedKeys[$key])) {
                $remainingEntries[$key] = array_sum($dayMap);
            }
        }
        arsort($remainingEntries);

        foreach ($rowMaterialMap as $row => $templateKey) {
            if (isset($resolvedMap[$row])) {
                continue;
            }

            $nextKey = array_key_first($remainingEntries);
            if ($nextKey === null) {
                break;
            }

            $resolvedMap[$row] = $nextKey;
            unset($remainingEntries[$nextKey]);
        }

        for ($row = $startRow; $row <= $endRow; $row++) {
            $key = $resolvedMap[$row] ?? null;
            $dayMap = $aggregate[$key] ?? [];

            if ($key !== null && isset($labels[$key])) {
                $sheet->setCellValue('B' . $row, $labels[$key]);
            }

            $rowTotal = 0;
            for ($day = 1; $day <= 31; $day++) {
                $col = Coordinate::stringFromColumnIndex(3 + $day);
                $value = (float) ($dayMap[$day] ?? 0);
                $sheet->setCellValue($col . $row, $value > 0 ? $value : '');
                $rowTotal += $value;
            }

            $sheet->setCellValue('AI' . $row, $rowTotal > 0 ? $rowTotal : '');
        }
    }

    private function buildAggregateByMaterialAndDay($rows, array $dateRange, bool $isAdditive): array
    {
        $aggregate = [];
        $labels = [];

        foreach ($rows as $row) {
            $material = $this->normalizeMaterialName((string) ($row->material_name ?? ''));
            if ($material === '') {
                continue;
            }

            if (!isset($labels[$material])) {
                $labels[$material] = trim((string) ($row->material_name ?? '-'));
            }

            foreach ($dateRange as $date) {
                $day = (int) Carbon::parse($date)->format('j');
                $dateKey = str_replace('-', '_', $date);

                if ($isAdditive) {
                    $preAlias = 'pre_date_' . $dateKey;
                    $adjAlias = 'date_' . $dateKey;
                    $value = (float) ($row->$preAlias ?? 0) + (float) ($row->$adjAlias ?? 0);
                } else {
                    $alias = 'date_' . $dateKey;
                    $value = (float) ($row->$alias ?? 0);
                }

                if (!isset($aggregate[$material][$day])) {
                    $aggregate[$material][$day] = 0;
                }

                $aggregate[$material][$day] += $value;
            }
        }

        return [
            'days' => $aggregate,
            'labels' => $labels,
        ];
    }

    private function normalizeMaterialName(string $name): string
    {
        $name = strtolower(trim($name));
        $name = preg_replace('/\s+/', ' ', $name);

        return preg_replace('/[^a-z0-9]/', '', $name);
    }

    private function buildLotCountsByDay($rows, array $dateRange, bool $isAdditive): array
    {
        $lotSetsByDay = [];

        foreach ($rows as $row) {
            $lotText = trim((string) ($row->lot ?? ''));
            if ($lotText === '' || $lotText === '-') {
                continue;
            }

            $lots = collect(preg_split('/\s*,\s*/', $lotText, -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn($lot) => trim((string) $lot))
                ->filter(fn($lot) => $lot !== '' && $lot !== '-')
                ->values();

            if ($lots->isEmpty()) {
                continue;
            }

            foreach ($dateRange as $date) {
                $day = (int) Carbon::parse($date)->format('j');
                $dateKey = str_replace('-', '_', $date);

                if ($isAdditive) {
                    $preAlias = 'pre_date_' . $dateKey;
                    $adjAlias = 'date_' . $dateKey;
                    $value = (float) ($row->$preAlias ?? 0) + (float) ($row->$adjAlias ?? 0);
                } else {
                    $alias = 'date_' . $dateKey;
                    $value = (float) ($row->$alias ?? 0);
                }

                if ($value <= 0) {
                    continue;
                }

                foreach ($lots as $lot) {
                    $lotSetsByDay[$day][$lot] = true;
                }
            }
        }

        $counts = [];
        foreach ($lotSetsByDay as $day => $lotSet) {
            $counts[$day] = count($lotSet);
        }

        return $counts;
    }

    public function render()
    {
        $this->loadProduct();
        return view('livewire.report.jsh.product-jsh');
    }
}
