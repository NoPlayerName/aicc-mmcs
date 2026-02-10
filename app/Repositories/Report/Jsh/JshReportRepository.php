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
                        'weight' => (float) $usage->weight
                    ];
                });
            });
        })->groupBy('material_id')
            ->map(function ($items, $materialId) {
                $row = [
                    'material_id' => $materialId,
                    'material_name' => $items->first()['material_name'] ?? '-'
                ];
                $total = 0;

                // Grouping per tanggal untuk kolom horizontal
                foreach ($items->groupBy('date') as $date => $usageGroup) {
                    $sumWeight = $usageGroup->sum('weight');
                    $alias = 'date_' . str_replace('-', '_', $date);
                    $row[$alias] = $sumWeight;
                    $total += $sumWeight;
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
            })
            ->when($furnace, function ($query) use ($furnace) {
                return $query->where('plan_furnace', $furnace);
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
                        'weight' => (float) $usage->weight
                    ];
                });
            });
        })->groupBy('material_id')
            ->map(function ($items, $materialId) {
                $row = [
                    'material_id' => $materialId,
                    'material_name' => $items->first()['material_name'] ?? '-'
                ];
                $total = 0;

                // Grouping per tanggal untuk kolom horizontal
                foreach ($items->groupBy('date') as $date => $usageGroup) {
                    $sumWeight = $usageGroup->sum('weight');
                    $alias = 'date_' . str_replace('-', '_', $date);
                    $row[$alias] = $sumWeight;
                    $total += $sumWeight;
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
                            'weight' => (float) $usage->weight
                        ];
                    });
                });
            })->groupBy('material_id')
                ->map(function ($items, $materialId) {
                    $row = [
                        'material_id' => $materialId,
                        'material_name' => $items->first()['material_name'] ?? '-',
                        'product_name' => $items->first()['product_name'] ?? '-',
                    ];
                    $total = 0;

                    // Grouping per tanggal untuk kolom horizontal
                    foreach ($items->groupBy('date') as $date => $usageGroup) {
                        $sumWeight = $usageGroup->sum('weight');
                        $alias = 'date_' . str_replace('-', '_', $date);
                        $row[$alias] = $sumWeight;
                        $total += $sumWeight;
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
