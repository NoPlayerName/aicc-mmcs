<?php

namespace App\Repositories\Report\Jsh;

use App\Enums\EnumTypeMat;
use App\Models\Jsh\ProdPlan;
use Illuminate\Support\Facades\Log;

class JshReportRepository implements JshReportRepositoryInterface
{
    public function reportTotalFurnace($startDate, $endDate, $type, $shift)
    {
        $plans = ProdPlan::with(['chargingHeads' => function ($query) {
            $query->with(['rawMatUse.materialable', 'additMatUse.materialable']);
        }])->whereBetween('plan_process_date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })
            ->get();

        return $plans->flatMap(function ($plan) use ($type) {
            return $plan->chargingHeads->flatMap(function ($head) use ($plan, $type) {
                $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                return $usages->map(function ($usage) use ($plan) {
                    return [
                        'material_id' => $usage->materialable_id,
                        'material_name' => $usage->materialable?->material_name,
                        'date' => $plan->plan_process_date,
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

                // Grouping per tanggal untuk kolom horizontal
                foreach ($items->groupBy('date') as $date => $usageGroup) {
                    $dateKey = str_replace('-', '_', $date);

                    if ($type === EnumTypeMat::Additive->value) {
                        // Logika khusus Additive
                        $preWeight = $usageGroup->where('type_adj', 1)->sum('weight');
                        $adjWeight = $usageGroup->where('type_adj', 2)->sum('weight');

                        $row['pre_date_' . $dateKey] = $preWeight;
                        $row['date_' . $dateKey]     = $adjWeight;
                    } else {
                        // Logika Raw Material (Tanpa P.ADJ)
                        $row['date_' . $dateKey] = $usageGroup->sum('weight');
                    }

                    $total += $usageGroup->sum('weight');
                }

                $row['subtotal'] = $total;
                return (object) $row;
            })
            ->values();
    }

    public function reportFurnace($startDate, $endDate, $type, $shift, $furnace)
    {
        $plans = ProdPlan::with(['chargingHeads' => function ($query) {
            $query->with(['rawMatUse.materialable', 'additMatUse.materialable']);
        }])->whereBetween('plan_process_date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })->where('plan_furnace', $furnace)
            ->get();

        return $plans->flatMap(function ($plan) use ($type) {
            return $plan->chargingHeads->flatMap(function ($head) use ($plan, $type) {
                $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                return $usages->map(function ($usage) use ($plan) {
                    return [
                        'material_id' => $usage->materialable_id,
                        'material_name' => $usage->materialable?->material_name,
                        'plan_furnace' => $plan->plan_furnace,
                        'date' => $plan->plan_process_date,
                        'weight' => (float) $usage->weight,
                        'type_adj' => $usage->type_additive,
                    ];
                });
            });
        })->groupBy('material_id')
            ->map(function ($items, $materialId) use ($type) {
                $row = [
                    'material_id' => $materialId,
                    'material_name' => $items->first()['material_name'] ?? '-',
                    'plan_furnace' => $items->first()['plan_furnace'] ?? '-',
                ];
                $total = 0;

                // Grouping per tanggal untuk kolom horizontal
                foreach ($items->groupBy('date') as $date => $usageGroup) {
                    $dateKey = str_replace('-', '_', $date);

                    if ($type === EnumTypeMat::Additive->value) {
                        // Logika khusus Additive
                        $preWeight = $usageGroup->where('type_adj', 1)->sum('weight');
                        $adjWeight = $usageGroup->where('type_adj', 2)->sum('weight');

                        $row['pre_date_' . $dateKey] = $preWeight;
                        $row['date_' . $dateKey]     = $adjWeight;
                    } else {
                        // Logika Raw Material (Tanpa P.ADJ)
                        $row['date_' . $dateKey] = $usageGroup->sum('weight');
                    }

                    $total += $usageGroup->sum('weight');
                }

                $row['subtotal'] = $total;
                return (object) $row;
            })
            ->values();
    }

    public function getKwhData($startDate, $endDate, $shift, $furnace)
    {
        $plans = ProdPlan::with(['chargingHeads' => function ($query) {
            $query->with(['Kwh']);
        }])->whereBetween('plan_process_date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })->where('plan_furnace', $furnace)
            ->get();

        $kwhList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargingHeads as $head) {
                if ($head->Kwh->isNotEmpty()) {
                    foreach ($head->Kwh as $kwh) {
                        $kwhList[] = [
                            'date' => $plan->plan_process_date,
                            'plan_furnace' => $plan->plan_furnace,
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
                    // Tampilkan charging meskipun belum ada KWH record
                    $kwhList[] = [
                        'date' => $plan->plan_process_date,
                        'plan_furnace' => $plan->plan_furnace,
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
        $plans = ProdPlan::with(['chargingHeads.TemptTapping'])
            ->whereBetween('plan_process_date', [$startDate, $endDate])
            ->when($shift, function ($query) use ($shift) {
                return $query->where('shift', $shift);
            })
            ->where('plan_furnace', $furnace)
            ->get();

        $tappingList = [];
        foreach ($plans as $plan) {
            foreach ($plan->chargingHeads as $head) {
                if ($head->TemptTapping->isNotEmpty()) {
                    foreach ($head->TemptTapping as $tapping) {
                        $tappingList[] = [
                            'date' => $plan->plan_process_date,
                            'plan_furnace' => $plan->plan_furnace,
                            'charging_id' => $head->id,
                            'charging' => $head->charging,
                            'lot' => $head->lot ?? '-',
                            'temperatur' => ($tapping->temperatur ?? 0),
                            'type_tapping' => $tapping->type_tapping?->text() ?? '-',
                        ];
                    }
                } else {
                    // Tampilkan charging meskipun belum ada tapping record
                    $tappingList[] = [
                        'date' => $plan->plan_process_date,
                        'plan_furnace' => $plan->plan_furnace,
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
        // Ambil data material usage seperti biasa
        $materialData = $this->reportFurnace($startDate, $endDate, $type, $shift, $furnace);

        // Ambil data KWH dan Temperature Tapping menggunakan method terpisah
        $kwhData = $this->getKwhData($startDate, $endDate, $shift, $furnace);
        $tappingData = $this->getTappingData($startDate, $endDate, $shift, $furnace);

        return [
            'materials' => $materialData,
            'kwh' => $kwhData,
            'tapping' => $tappingData,
        ];
    }

    public function reportProduct($startDate, $endDate, $type, $shift, $product)
    {
        try {
            $plans = ProdPlan::with(['chargingHeads' => function ($query) {
                $query->with(['rawMatUse.materialable', 'additMatUse.materialable']);
            }, 'models'])->whereBetween('plan_process_date', [$startDate, $endDate])
                ->where('shift', $shift)
                ->where('model_id', $product)
                ->get();

            return $plans->flatMap(function ($plan) use ($type) {
                return $plan->chargingHeads->flatMap(function ($head) use ($plan, $type) {
                    $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                    return $usages->map(function ($usage) use ($plan) {
                        return [
                            'material_id' => $usage->materialable_id,
                            'material_name' => $usage->materialable?->material_name,
                            'product_name' => $plan->models?->model . ' - ' . $plan->models?->alias,
                            'date' => $plan->plan_process_date,
                            'weight' => $usage->weight,
                            'type_adj' => $usage->type_additive,
                        ];
                    });
                });
            })->groupBy('material_id')
                ->map(function ($items, $materialId) use ($type) {
                    $row = [
                        'material_id' => $materialId,
                        'material_name' => $items->first()['material_name'] ?? '-',
                        'product_name' => $items->first()['product_name'] ?? '-',
                    ];
                    $total = 0;

                    foreach ($items->groupBy('date') as $date => $usageGroup) {
                        $dateKey = str_replace('-', '_', $date);

                        if ($type === EnumTypeMat::Additive->value) {
                            $preWeight = $usageGroup->where('type_adj', 1)->sum('weight');
                            $adjWeight = $usageGroup->where('type_adj', 2)->sum('weight');

                            $row['pre_date_' . $dateKey] = $preWeight;
                            $row['date_' . $dateKey]     = $adjWeight;
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
            Log::error('Generate report product fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
}
