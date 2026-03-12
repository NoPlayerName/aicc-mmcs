<?php

namespace App\Repositories\MaterialUseJsh;

use App\Enums\EnumTypeMat;
use App\Models\Jsh\MaterialUse\MaterialAdjustJsh;
use App\Models\Jsh\MaterialUse\ChargingHead;
use App\Models\Jsh\MaterialUse\MaterialUsageJsh;
use App\Models\Jsh\ProdPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialAdjustJshRepository implements MaterialAdjustJshRepositoryInterface
{
    public function saveBulk(array $rows): bool
    {
        DB::beginTransaction();
        try {
            MaterialAdjustJsh::insert($rows);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save material adjust jsh fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function existsPlanDate(string $transactionDate): bool
    {
        return ProdPlan::whereDate('plan_process_date', $transactionDate)->exists();
    }

    public function getReport(?string $startDate, ?string $endDate, ?string $materialCode = null)
    {
        $query = MaterialAdjustJsh::with('materialable')
            ->orderBy('transaction_date')
            ->orderBy('id');

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        if (!empty($materialCode)) {
            $query->where('materialable_id', $materialCode);
        }

        return $query->get();
    }

    public function getCombinedReport(?string $startDate, ?string $endDate, ?string $materialCode = null)
    {
        $summary = [];

        $planQuery = ProdPlan::select('production_plan_id', 'plan_process_date');
        if (!empty($startDate) && !empty($endDate)) {
            $planQuery->whereBetween('plan_process_date', [$startDate, $endDate]);
        }

        $plans = $planQuery->get();
        $planDateById = $plans->pluck('plan_process_date', 'production_plan_id')->toArray();

        if (!empty($planDateById)) {
            $chargingHeads = ChargingHead::select('id', 'plan_id_anchor')
                ->whereIn('plan_id_anchor', array_keys($planDateById))
                ->get();

            $chargingPlanById = $chargingHeads->pluck('plan_id_anchor', 'id')->toArray();

            if (!empty($chargingPlanById)) {
                $usageQuery = MaterialUsageJsh::with('materialable')
                    ->select('charging_head_id', 'materialable_id', 'materialable_type', 'weight', 'type')
                    ->whereIn('charging_head_id', array_keys($chargingPlanById));

                if (!empty($materialCode)) {
                    $usageQuery->where('materialable_id', $materialCode);
                }

                $usages = $usageQuery->get();

                foreach ($usages as $item) {
                    $planId = $chargingPlanById[$item->charging_head_id] ?? null;
                    $transactionDate = $planId ? ($planDateById[$planId] ?? null) : null;
                    if (!$transactionDate) {
                        continue;
                    }

                    $key = $transactionDate . '|' . $item->materialable_id;
                    $materialName = $item->materialable?->material_name ?? '-';
                    $typeValue = $item->type instanceof EnumTypeMat
                        ? $item->type->value
                        : (int) $item->type;
                    $materialType = ($typeValue === EnumTypeMat::RawMaterial->value) ? 'RAW' : 'ADDITIVE';

                    if (!isset($summary[$key])) {
                        $summary[$key] = [
                            'transaction_date' => $transactionDate,
                            'material_id' => $item->materialable_id,
                            'material_name' => $materialName,
                            'material_type' => $materialType,
                            'usage_qty' => 0,
                            'adjust_qty' => 0,
                        ];
                    }

                    $summary[$key]['usage_qty'] += (float) $item->weight;
                }
            }
        }

        $adjustQuery = MaterialAdjustJsh::with('materialable')
            ->orderBy('transaction_date');

        if (!empty($startDate) && !empty($endDate)) {
            $adjustQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        if (!empty($materialCode)) {
            $adjustQuery->where('materialable_id', $materialCode);
        }

        $adjustments = $adjustQuery->get();

        foreach ($adjustments as $item) {
            $transactionDate = optional($item->transaction_date)->format('Y-m-d');
            if (!$transactionDate) {
                continue;
            }

            $key = $transactionDate . '|' . $item->materialable_id;
            $materialName = $item->materialable?->material_name ?? '-';
            $typeValue = $item->materialable?->is_for_mmcs ?? null;
            $materialType = $typeValue == '1' ? 'RAW' : ($typeValue == '2' ? 'ADDITIVE' : '-');

            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'transaction_date' => $transactionDate,
                    'material_id' => $item->materialable_id,
                    'material_name' => $materialName,
                    'material_type' => $materialType,
                    'usage_qty' => 0,
                    'adjust_qty' => 0,
                ];
            }

            if (($summary[$key]['material_name'] ?? '-') === '-' && $materialName !== '-') {
                $summary[$key]['material_name'] = $materialName;
            }

            if (($summary[$key]['material_type'] ?? '-') === '-' && $materialType !== '-') {
                $summary[$key]['material_type'] = $materialType;
            }

            $summary[$key]['adjust_qty'] += (float) $item->qty_adjust;
        }

        $rows = collect(array_values($summary))
            ->map(function ($row) {
                $row['final_qty'] = (float) $row['usage_qty'] + (float) $row['adjust_qty'];
                return $row;
            })
            ->sortBy(['transaction_date', 'material_name'])
            ->values()
            ->toArray();

        return $rows;
    }
}
