<?php

namespace App\Repositories\Report\Jsh;

use App\Enums\EnumTypeMat;
use App\Models\Jsh\MaterialUse\FurnaceHead;
use App\Models\Jsh\ProdPlan;
use Illuminate\Support\Facades\Log;

class JshReportRepository implements JshReportRepositoryInterface
{
    public function reportTotalFurnace($startDate, $endDate, $type, $shift)
    {
        try {
            $plans = FurnaceHead::with(['chargingHeads' => function ($query) {
                $query->with(['rawMatUse.materialable', 'additMatUse.materialable']);
            }])->whereBetween('date', [$startDate, $endDate])
                ->where('shift', $shift)
                ->get();

            return $plans->flatMap(function ($plan) use ($type) {
                return $plan->chargingHeads->flatMap(function ($head) use ($plan, $type) {
                    $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                    return $usages->map(function ($usage) use ($plan) {
                        return [
                            'material_id' => $usage->materialable_id,
                            'material_name' => $usage->materialable?->material_name,
                            'date' => $plan->date,
                            'weight' => (float) $usage->weight,
                            'type_adj' => $usage->type_additive,
                        ];
                    });
                });
            })->groupBy('material_id')
                ->map(function ($items, $materialId) use ($type) {
                    $row = [
                        'material_id' => $materialId,
                        'material_name' => $items->first()['material_name'] ?? '-'
                    ];
                    $total = 0;

                    foreach ($items->groupBy('date') as $date => $usageGroup) {
                        $dateKey = str_replace('-', '_', $date);

                        if ($type === EnumTypeMat::Additive->value) {
                            $preWeight = $usageGroup->where('type_adj', 1)->sum('weight');
                            $adjWeight = $usageGroup->where('type_adj', 2)->sum('weight');

                            $row['pre_date_' . $dateKey] = $preWeight;
                            $row['date_' . $dateKey] = $adjWeight;

                            // Keep subtotal consistent with displayed additive columns (P.ADJ + ADJ)
                            $total += ($preWeight + $adjWeight);
                        } else {
                            $row['date_' . $dateKey] = $usageGroup->sum('weight');

                            $total += $usageGroup->sum('weight');
                        }
                    }

                    $row['subtotal'] = $total;
                    return (object) $row;
                })
                ->values();
        } catch (\Throwable $th) {
            Log::error('Generate report all furnace fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function reportFurnace($startDate, $endDate, $type, $shift, $furnace)
    {
        try {
            $plans = FurnaceHead::with(['chargingHeads' => function ($query) {
                $query->with(['rawMatUse.materialable', 'additMatUse.materialable']);
            }])->whereBetween('date', [$startDate, $endDate])
                ->when($shift, function ($query) use ($shift) {
                    return $query->where('shift', $shift);
                })
                ->where('furnace', $furnace)
                ->get();

            $planLotMap = $this->buildLotMapFromPlans($plans);

            return $plans->flatMap(function ($plan) use ($type, $planLotMap) {
                return $plan->chargingHeads->flatMap(function ($head) use ($plan, $type, $planLotMap) {
                    $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                    return $usages->map(function ($usage) use ($plan, $head, $planLotMap) {
                        return [
                            'material_id' => $usage->materialable_id,
                            'material_name' => $usage->materialable?->material_name,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $this->resolveLotByPlanMap($plan, $planLotMap, $head),
                            'plan_furnace' => $plan->furnace,
                            'date' => $plan->date,
                            'weight' => (float) $usage->weight,
                            'type_adj' => $usage->type_additive,
                        ];
                    });
                });
            })->groupBy(fn($item) => $item['material_id'] . '|' . $item['charging_id'] . '|' . ($item['lot'] ?? '-'))
                ->map(function ($items) use ($type) {
                    $row = [
                        'material_id' => $items->first()['material_id'] ?? '-',
                        'material_name' => $items->first()['material_name'] ?? '-',
                        'charging' => $items->first()['charging'] ?? '-',
                        'lot' => $items->first()['lot'] ?? '-',
                        'plan_furnace' => $items->first()['plan_furnace'] ?? '-',
                    ];
                    $total = 0;

                    foreach ($items->groupBy('date') as $date => $usageGroup) {
                        $dateKey = str_replace('-', '_', $date);

                        if ($type === EnumTypeMat::Additive->value) {
                            $preWeight = $usageGroup->where('type_adj', 1)->sum('weight');
                            $adjWeight = $usageGroup->where('type_adj', 2)->sum('weight');

                            $row['pre_date_' . $dateKey] = $preWeight;
                            $row['date_' . $dateKey] = $adjWeight;

                            // Keep subtotal consistent with displayed additive columns (P.ADJ + ADJ)
                            $total += ($preWeight + $adjWeight);
                        } else {
                            $row['date_' . $dateKey] = $usageGroup->sum('weight');

                            $total += $usageGroup->sum('weight');
                        }
                    }

                    $row['subtotal'] = $total;
                    return (object) $row;
                })
                ->sort(function ($a, $b) {
                    $dateCompare = strcmp((string) ($a->date ?? ''), (string) ($b->date ?? ''));
                    if ($dateCompare !== 0) {
                        return $dateCompare;
                    }

                    $chargingCompare = ((int) ($a->charging ?? 0)) <=> ((int) ($b->charging ?? 0));
                    if ($chargingCompare !== 0) {
                        return $chargingCompare;
                    }

                    $materialCompare = strcmp((string) ($a->material_name ?? ''), (string) ($b->material_name ?? ''));
                    if ($materialCompare !== 0) {
                        return $materialCompare;
                    }

                    return strcmp((string) ($a->lot ?? ''), (string) ($b->lot ?? ''));
                })
                ->values();
        } catch (\Throwable $th) {
            Log::error('Generate report furnace fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function getKwhData($startDate, $endDate, $shift, $furnace)
    {
        $plans = FurnaceHead::with(['chargingHeads' => function ($query) {
            $query->with(['Kwh']);
        }])->whereBetween('date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })->where('furnace', $furnace)
            ->get();

        $planLotMap = $this->buildLotMapFromPlans($plans);

        $kwhList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargingHeads as $head) {
                $planLot = $this->resolveLotByPlanMap($plan, $planLotMap, $head);
                if ($head->Kwh->isNotEmpty()) {
                    foreach ($head->Kwh as $kwh) {
                        $kwhList[] = [
                            'date' => $plan->date,
                            'plan_furnace' => $plan->furnace,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $planLot,
                            'charge_time' => $kwh->charge_time ?? '-',
                            'kwh_start_charge' => $kwh->kwh_start_charge ?? 0,
                            'kwh_ok_charge' => $kwh->kwh_ok_charge ?? 0,
                            'power' => $kwh->power ?? 0,
                        ];
                    }
                } else {
                    // Tampilkan charging meskipun belum ada KWH record
                    $kwhList[] = [
                        'date' => $plan->date,
                        'plan_furnace' => $plan->furnace,
                        'charging_id' => $head->id,
                        'charging' => $head->charging,
                        'lot' => $planLot,
                        'charge_time' => '-',
                        'kwh_start_charge' => 0,
                        'kwh_ok_charge' => 0,
                        'power' => 0,
                    ];
                }
            }
        }

        return collect($kwhList)
            ->sort(function ($a, $b) {
                $dateCompare = strcmp((string) ($a['date'] ?? ''), (string) ($b['date'] ?? ''));
                if ($dateCompare !== 0) {
                    return $dateCompare;
                }

                $chargingCompare = ((int) ($a['charging'] ?? 0)) <=> ((int) ($b['charging'] ?? 0));
                if ($chargingCompare !== 0) {
                    return $chargingCompare;
                }

                $lotCompare = strcmp((string) ($a['lot'] ?? ''), (string) ($b['lot'] ?? ''));
                if ($lotCompare !== 0) {
                    return $lotCompare;
                }

                return 0;
            })
            ->values()
            ->toArray();
    }

    public function getTappingData($startDate, $endDate, $shift, $furnace)
    {
        $plans = FurnaceHead::with(['chargingHeads.TemptTapping'])
            ->whereBetween('date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })
            ->where('furnace', $furnace)
            ->get();

        $planLotMap = $this->buildLotMapFromPlans($plans);

        $tappingList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargingHeads as $head) {
                $planLot = $this->resolveLotByPlanMap($plan, $planLotMap, $head);
                if ($head->TemptTapping->isNotEmpty()) {
                    foreach ($head->TemptTapping as $tapping) {
                        $tappingList[] = [
                            'date' => $plan->date,
                            'plan_furnace' => $plan->furnace,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $planLot,
                            'temperatur' => ($tapping->temperatur ?? 0),
                            'type_tapping' => $tapping->type_tapping?->text() ?? '-',
                        ];
                    }
                } else {
                    // Tampilkan charging meskipun belum ada tapping record
                    $tappingList[] = [
                        'date' => $plan->date,
                        'plan_furnace' => $plan->furnace,
                        'charging_id' => $head->id,
                        'charging' => $head->charging,
                        'lot' => $planLot,
                        'temperatur' => 0,
                        'type_tapping' => '-',
                    ];
                }
            }
        }

        return collect($tappingList)
            ->sort(function ($a, $b) {
                $dateCompare = strcmp((string) ($a['date'] ?? ''), (string) ($b['date'] ?? ''));
                if ($dateCompare !== 0) {
                    return $dateCompare;
                }

                $chargingCompare = ((int) ($a['charging'] ?? 0)) <=> ((int) ($b['charging'] ?? 0));
                if ($chargingCompare !== 0) {
                    return $chargingCompare;
                }

                $lotCompare = strcmp((string) ($a['lot'] ?? ''), (string) ($b['lot'] ?? ''));
                if ($lotCompare !== 0) {
                    return $lotCompare;
                }

                return 0;
            })
            ->values()
            ->toArray();
    }

    public function reportFurnaceWithKwhTapping($startDate, $endDate, $type, $shift, $furnace)
    {
        $materialData = $this->reportFurnace($startDate, $endDate, $type, $shift, $furnace);
        $kwhData = $this->getKwhData($startDate, $endDate, $shift, $furnace);
        $tappingData = $this->getTappingData($startDate, $endDate, $shift, $furnace);

        return [
            'materials' => $materialData,
            'kwh' => $kwhData,
            'tapping' => $tappingData,
        ];
    }

    public function reportProduct($startDate, $endDate, $type, $shift, $product, $furnace = null)
    {
        try {
            $plans = FurnaceHead::with(['chargingHeads' => function ($query) use ($product) {
                $query->with(['product', 'rawMatUse.materialable', 'additMatUse.materialable'])
                    ->where('model_id', $product);
            }])->whereBetween('date', [$startDate, $endDate])
                ->when($shift, function ($query) use ($shift) {
                    return $query->where('shift', $shift);
                })
                ->when($furnace, function ($query) use ($furnace) {
                    return $query->where('furnace', $furnace);
                })
                ->get();

            $planLotMap = $this->buildLotMapFromPlans($plans);

            return $plans->flatMap(function ($plan) use ($type, $planLotMap) {
                return $plan->chargingHeads->flatMap(function ($head) use ($plan, $type, $planLotMap) {
                    $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                    return $usages->map(function ($usage) use ($plan, $head, $planLotMap) {
                        return [
                            'material_id' => $usage->materialable_id,
                            'material_name' => $usage->materialable?->material_name,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $this->resolveLotByPlanMap($plan, $planLotMap, $head),
                            'product_name' => trim(($head->product?->model ?? '-') . ' - ' . ($head->product?->alias ?? '-')),
                            'date' => $plan->date,
                            'weight' => (float) $usage->weight,
                            'type_adj' => $usage->type_additive,
                        ];
                    });
                });
            })->groupBy(fn($item) => $item['material_id'] . '|' . $item['charging_id'] . '|' . ($item['lot'] ?? '-'))
                ->map(function ($items) use ($type) {
                    $row = [
                        'material_id' => $items->first()['material_id'] ?? '-',
                        'material_name' => $items->first()['material_name'] ?? '-',
                        'charging' => $items->first()['charging'] ?? '-',
                        'lot' => $items->first()['lot'] ?? '-',
                        'product_name' => $items->first()['product_name'] ?? '-',
                    ];
                    $total = 0;

                    foreach ($items->groupBy('date') as $date => $usageGroup) {
                        $dateKey = str_replace('-', '_', $date);

                        if ($type === EnumTypeMat::Additive->value) {
                            $preWeight = $usageGroup->where('type_adj', 1)->sum('weight');
                            $adjWeight = $usageGroup->where('type_adj', 2)->sum('weight');

                            $row['pre_date_' . $dateKey] = $preWeight;
                            $row['date_' . $dateKey] = $adjWeight;

                            // Keep subtotal consistent with displayed additive columns (P.ADJ + ADJ)
                            $total += ($preWeight + $adjWeight);
                        } else {
                            $row['date_' . $dateKey] = $usageGroup->sum('weight');

                            $total += $usageGroup->sum('weight');
                        }
                    }

                    $row['subtotal'] = $total;
                    return (object) $row;
                })
                ->sort(function ($a, $b) {
                    $chargingCompare = ((int) ($a->charging ?? 0)) <=> ((int) ($b->charging ?? 0));
                    if ($chargingCompare !== 0) {
                        return $chargingCompare;
                    }

                    $lotCompare = strcmp((string) ($a->lot ?? ''), (string) ($b->lot ?? ''));
                    if ($lotCompare !== 0) {
                        return $lotCompare;
                    }

                    $materialCompare = strcmp((string) ($a->material_name ?? ''), (string) ($b->material_name ?? ''));
                    if ($materialCompare !== 0) {
                        return $materialCompare;
                    }

                    return 0;
                })
                ->values();
        } catch (\Throwable $th) {
            Log::error('Generate report product fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function getKwhDataByProduct($startDate, $endDate, $shift, $product, $furnace = null)
    {
        $plans = FurnaceHead::with(['chargingHeads' => function ($query) use ($product) {
            $query->with(['product', 'Kwh'])->where('model_id', $product);
        }])->whereBetween('date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })
            ->when($furnace, function ($query) use ($furnace) {
                return $query->where('furnace', $furnace);
            })
            ->get();

        $planLotMap = $this->buildLotMapFromPlans($plans);

        $kwhList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargingHeads as $head) {
                $planLot = $this->resolveLotByPlanMap($plan, $planLotMap, $head);
                if ($head->Kwh->isNotEmpty()) {
                    foreach ($head->Kwh as $kwh) {
                        $kwhList[] = [
                            'date' => $plan->date,
                            'plan_furnace' => $plan->furnace,
                            'product_name' => trim(($head->product?->model ?? '-') . ' - ' . ($head->product?->alias ?? '-')),
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $planLot,
                            'charge_time' => $kwh->charge_time ?? '-',
                            'kwh_start_charge' => $kwh->kwh_start_charge ?? 0,
                            'kwh_ok_charge' => $kwh->kwh_ok_charge ?? 0,
                            'power' => $kwh->power ?? 0,
                        ];
                    }
                } else {
                    $kwhList[] = [
                        'date' => $plan->date,
                        'plan_furnace' => $plan->furnace,
                        'product_name' => trim(($head->product?->model ?? '-') . ' - ' . ($head->product?->alias ?? '-')),
                        'charging_id' => $head->id,
                        'charging' => $head->charging,
                        'lot' => $planLot,
                        'charge_time' => '-',
                        'kwh_start_charge' => 0,
                        'kwh_ok_charge' => 0,
                        'power' => 0,
                    ];
                }
            }
        }

        return collect($kwhList)
            ->sort(function ($a, $b) {
                $dateCompare = strcmp((string) ($a['date'] ?? ''), (string) ($b['date'] ?? ''));
                if ($dateCompare !== 0) {
                    return $dateCompare;
                }

                $chargingCompare = ((int) ($a['charging'] ?? 0)) <=> ((int) ($b['charging'] ?? 0));
                if ($chargingCompare !== 0) {
                    return $chargingCompare;
                }

                return strcmp((string) ($a['lot'] ?? ''), (string) ($b['lot'] ?? ''));
            })
            ->values()
            ->toArray();
    }

    public function getTappingDataByProduct($startDate, $endDate, $shift, $product, $furnace = null)
    {
        $plans = FurnaceHead::with(['chargingHeads' => function ($query) use ($product) {
            $query->with(['product', 'TemptTapping'])->where('model_id', $product);
        }])->whereBetween('date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })
            ->when($furnace, function ($query) use ($furnace) {
                return $query->where('furnace', $furnace);
            })
            ->get();

        $planLotMap = $this->buildLotMapFromPlans($plans);

        $tappingList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargingHeads as $head) {
                $planLot = $this->resolveLotByPlanMap($plan, $planLotMap, $head);
                if ($head->TemptTapping->isNotEmpty()) {
                    foreach ($head->TemptTapping as $tapping) {
                        $tappingList[] = [
                            'date' => $plan->date,
                            'plan_furnace' => $plan->furnace,
                            'product_name' => trim(($head->product?->model ?? '-') . ' - ' . ($head->product?->alias ?? '-')),
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $planLot,
                            'temperatur' => ($tapping->temperatur ?? 0),
                            'type_tapping' => $tapping->type_tapping?->text() ?? '-',
                        ];
                    }
                } else {
                    $tappingList[] = [
                        'date' => $plan->date,
                        'plan_furnace' => $plan->furnace,
                        'product_name' => trim(($head->product?->model ?? '-') . ' - ' . ($head->product?->alias ?? '-')),
                        'charging_id' => $head->id,
                        'charging' => $head->charging,
                        'lot' => $planLot,
                        'temperatur' => 0,
                        'type_tapping' => '-',
                    ];
                }
            }
        }

        return collect($tappingList)
            ->sort(function ($a, $b) {
                $dateCompare = strcmp((string) ($a['date'] ?? ''), (string) ($b['date'] ?? ''));
                if ($dateCompare !== 0) {
                    return $dateCompare;
                }

                $chargingCompare = ((int) ($a['charging'] ?? 0)) <=> ((int) ($b['charging'] ?? 0));
                if ($chargingCompare !== 0) {
                    return $chargingCompare;
                }

                return strcmp((string) ($a['lot'] ?? ''), (string) ($b['lot'] ?? ''));
            })
            ->values()
            ->toArray();
    }

    public function reportProductWithKwhTapping($startDate, $endDate, $type, $shift, $product, $furnace = null)
    {
        $materialData = $this->reportProduct($startDate, $endDate, $type, $shift, $product, $furnace);
        $kwhData = $this->getKwhDataByProduct($startDate, $endDate, $shift, $product, $furnace);
        $tappingData = $this->getTappingDataByProduct($startDate, $endDate, $shift, $product, $furnace);

        return [
            'materials' => $materialData,
            'kwh' => $kwhData,
            'tapping' => $tappingData,
        ];
    }

    private function buildLotMapFromPlans($plans): array
    {
        $lotMap = [];

        $plans
            ->groupBy(function ($plan) {
                return implode('|', [
                    (string) ($plan->date ?? ''),
                    (string) ($plan->shift ?? ''),
                    (string) ($plan->furnace ?? ''),
                ]);
            })
            ->each(function ($groupPlans) use (&$lotMap) {
                $sortedPlans = $groupPlans
                    ->sort(function ($a, $b) {
                        $lotA = $this->getLotNumber($a);
                        $lotB = $this->getLotNumber($b);

                        if ($lotA !== $lotB) {
                            return $lotA <=> $lotB;
                        }

                        return strcmp((string) ($a->id ?? ''), (string) ($b->id ?? ''));
                    })
                    ->values();

                $planGroups = [];
                $currentGroup = [];

                foreach ($sortedPlans as $plan) {
                    if (empty($currentGroup)) {
                        $currentGroup[] = $plan;
                        continue;
                    }

                    $lastPlan = end($currentGroup);
                    $lastLot = $this->getLotNumber($lastPlan);
                    $currentLot = $this->getLotNumber($plan);

                    if ($currentLot === ($lastLot + 1) && count($currentGroup) < 3) {
                        $currentGroup[] = $plan;
                    } else {
                        $planGroups[] = $currentGroup;
                        $currentGroup = [$plan];
                    }
                }

                if (!empty($currentGroup)) {
                    $planGroups[] = $currentGroup;
                }

                foreach ($planGroups as $planGroup) {
                    $lotText = collect($planGroup)
                        ->pluck('lot')
                        ->map(function ($value) {
                            return trim((string) $value);
                        })
                        ->filter(function ($value) {
                            return $value !== '';
                        })
                        ->implode(', ');

                    $lotValue = $lotText !== '' ? $lotText : '-';

                    foreach ($planGroup as $plan) {
                        $planId = (string) ($plan->id ?? '');
                        if ($planId !== '') {
                            $lotMap[$planId] = $lotValue;
                        }
                    }
                }
            });

        return $lotMap;
    }

    private function getLotNumber($plan): int
    {
        $lot = trim((string) data_get($plan, 'lot', ''));

        if ($lot === '') {
            return PHP_INT_MAX;
        }

        if (is_numeric($lot)) {
            return (int) $lot;
        }

        if (preg_match('/\d+/', $lot, $matches) === 1) {
            return (int) $matches[0];
        }

        return PHP_INT_MAX;
    }

    private function resolveLotByPlanMap($plan, array $planLotMap, $head = null): string
    {
        $planId = (string) ($plan->id ?? '');

        if ($planId !== '' && isset($planLotMap[$planId]) && $planLotMap[$planId] !== '-') {
            return $planLotMap[$planId];
        }

        $lotFromPlan = $this->resolveLotFromPlan($plan);
        if ($lotFromPlan !== '-') {
            return $lotFromPlan;
        }

        if ($head !== null) {
            $headLot = trim((string) data_get($head, 'lot', ''));
            if ($headLot !== '') {
                return $headLot;
            }
        }

        return '-';
    }

    private function resolveLotFromPlan($plan): string
    {
        $rawLots = [];

        $candidateFields = ['lot', 'lot1', 'lot2', 'lot3', 'lot_1', 'lot_2', 'lot_3'];
        foreach ($candidateFields as $field) {
            $value = data_get($plan, $field);
            if (is_scalar($value) && trim((string) $value) !== '') {
                $rawLots[] = (string) $value;
            }
        }

        $lots = collect($rawLots)
            ->flatMap(function ($value) {
                return preg_split('/\s*,\s*/', (string) $value, -1, PREG_SPLIT_NO_EMPTY);
            })
            ->map(function ($value) {
                return trim((string) $value);
            })
            ->filter()
            ->unique()
            ->take(3)
            ->values();

        return $lots->isEmpty() ? '-' : $lots->implode(', ');
    }
}
