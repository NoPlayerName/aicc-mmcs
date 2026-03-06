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
}
