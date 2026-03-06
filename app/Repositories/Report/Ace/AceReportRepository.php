<?php

namespace App\Repositories\Report\Ace;

use App\Enums\EnumTypeMat;
use App\Models\Ace\MaterialUse\FurnaceHeadAce;
use Illuminate\Support\Facades\Log;

class AceReportRepository implements AceReportRepositoryInterface
{
    public function reportTotalFurnace($startDate, $endDate, $type, $shift)
    {
        try {
            $plans = FurnaceHeadAce::with(['chargings' => function ($query) {
                $query->with(['rawMatUse.materialable', 'additMatUse.materialable']);
            }])->whereBetween('date', [$startDate, $endDate])
                ->where('shift', $shift)
                ->get();

            return $plans->flatMap(function ($plan) use ($type) {
                return $plan->chargings->flatMap(function ($head) use ($plan, $type) {
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
                        } else {
                            $row['date_' . $dateKey] = $usageGroup->sum('weight');
                        }

                        $total += $usageGroup->sum('weight');
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
            $plans = FurnaceHeadAce::with(['chargings' => function ($query) {
                $query->with(['rawMatUse.materialable', 'additMatUse.materialable']);
            }])->whereBetween('date', [$startDate, $endDate])
                ->when($shift, function ($query) use ($shift) {
                    return $query->where('shift', $shift);
                })
                ->where('furnace', $furnace)
                ->get();

            return $plans->flatMap(function ($plan) use ($type) {
                return $plan->chargings->flatMap(function ($head) use ($plan, $type) {
                    $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                    return $usages->map(function ($usage) use ($plan, $head) {
                        return [
                            'material_id' => $usage->materialable_id,
                            'material_name' => $usage->materialable?->material_name,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $head->lot ?? '-',
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
                        } else {
                            $row['date_' . $dateKey] = $usageGroup->sum('weight');
                        }

                        $total += $usageGroup->sum('weight');
                    }

                    $row['subtotal'] = $total;
                    return (object) $row;
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
        $plans = FurnaceHeadAce::with(['chargings' => function ($query) {
            $query->with(['Kwh']);
        }])->whereBetween('date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })->where('furnace', $furnace)
            ->get();

        $kwhList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargings as $head) {
                if ($head->Kwh->isNotEmpty()) {
                    foreach ($head->Kwh as $kwh) {
                        $kwhList[] = [
                            'date' => $plan->date,
                            'plan_furnace' => $plan->furnace,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $head->lot ?? '-',
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
                        'charging_id' => $head->id,
                        'charging' => $head->charging,
                        'lot' => $head->lot ?? '-',
                        'charge_time' => '-',
                        'kwh_start_charge' => 0,
                        'kwh_ok_charge' => 0,
                        'power' => 0,
                    ];
                }
            }
        }

        return $kwhList;
    }

    public function getTappingData($startDate, $endDate, $shift, $furnace)
    {
        $plans = FurnaceHeadAce::with(['chargings.TemptTapping'])
            ->whereBetween('date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })
            ->where('furnace', $furnace)
            ->get();

        $tappingList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargings as $head) {
                if ($head->TemptTapping->isNotEmpty()) {
                    foreach ($head->TemptTapping as $tapping) {
                        $tappingList[] = [
                            'date' => $plan->date,
                            'plan_furnace' => $plan->furnace,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $head->lot ?? '-',
                            'temperatur' => ($tapping->temperatur ?? 0),
                            'type_tapping' => $tapping->type_tapping?->text() ?? '-',
                        ];
                    }
                } else {
                    $tappingList[] = [
                        'date' => $plan->date,
                        'plan_furnace' => $plan->furnace,
                        'charging_id' => $head->id,
                        'charging' => $head->charging,
                        'lot' => $head->lot ?? '-',
                        'temperatur' => 0,
                        'type_tapping' => '-',
                    ];
                }
            }
        }

        return $tappingList;
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
}
