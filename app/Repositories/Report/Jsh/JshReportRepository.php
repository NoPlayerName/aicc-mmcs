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
            $query->with(['rawMatUse.material', 'additMatUse.material']);
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
                        'material_id' => $usage->material_id,
                        'material_name' => $usage->material?->material_name,
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
            $query->with(['rawMatUse.material', 'additMatUse.material']);
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
                        'material_id' => $usage->material_id,
                        'material_name' => $usage->material?->material_name,
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
    public function reportProduct($startDate, $endDate, $type, $shift, $product)
    {
        // $id = (int)$product;
        // dd($startDate, $endDate, $type, $shift, $product);
        try {
            $plans = ProdPlan::with(['chargingHeads' => function ($query) {
                $query->with(['rawMatUse.material', 'additMatUse.material']);
            }, 'models'])->whereBetween('plan_process_date', [$startDate, $endDate])
                ->where('shift', $shift)
                ->where('model_id', $product)
                ->get();
            // dd($plans);
            return $plans->flatMap(function ($plan) use ($type) {
                return $plan->chargingHeads->flatMap(function ($head) use ($plan, $type) {
                    $usages = ($type === EnumTypeMat::RawMaterial->value) ? $head->rawMatUse : $head->additMatUse;
                    return $usages->map(function ($usage) use ($plan) {
                        return [
                            'material_id' => $usage->material_id,
                            'material_name' => $usage->material?->material_name,
                            'product_name' => $plan->models?->model . ' - ' . $plan->models?->alias,
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
                        'product_name' => $items->first()['product_name'] ?? '-',
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
        } catch (\Throwable $th) {
            Log::error('Generate report product fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
}
