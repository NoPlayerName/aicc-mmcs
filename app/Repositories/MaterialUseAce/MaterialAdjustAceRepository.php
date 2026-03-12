<?php

namespace App\Repositories\MaterialUseAce;

use App\Enums\EnumTypeMat;
use App\Models\Ace\MaterialUse\ChargingHeadAce;
use App\Models\Ace\MaterialUse\FurnaceHeadAce;
use App\Models\Ace\MaterialUse\MaterialAdjustAce;
use App\Models\Ace\MaterialUse\MaterialUsageAce;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialAdjustAceRepository implements MaterialAdjustAceRepositoryInterface
{
    public function saveBulk(array $rows): bool
    {
        DB::beginTransaction();
        try {
            MaterialAdjustAce::insert($rows);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save material adjust ace fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function existsPlanDate(string $transactionDate): bool
    {
        return FurnaceHeadAce::whereDate('date', $transactionDate)->exists();
    }

    public function getCombinedReport(?string $startDate, ?string $endDate, ?string $materialCode = null)
    {
        $summary = [];

        $furnaceQuery = FurnaceHeadAce::select('id', 'date');
        if (!empty($startDate) && !empty($endDate)) {
            $furnaceQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $furnaces = $furnaceQuery->get();
        $furnaceDateById = $furnaces->pluck('date', 'id')->toArray();

        if (!empty($furnaceDateById)) {
            $chargingHeads = ChargingHeadAce::select('id', 'plan_id_anchor')
                ->whereIn('plan_id_anchor', array_keys($furnaceDateById))
                ->get();

            $chargingFurnaceById = $chargingHeads->pluck('plan_id_anchor', 'id')->toArray();

            if (!empty($chargingFurnaceById)) {
                $usageQuery = MaterialUsageAce::with('materialable')
                    ->select('charging_head_id', 'materialable_id', 'materialable_type', 'weight', 'type')
                    ->whereIn('charging_head_id', array_keys($chargingFurnaceById));

                if (!empty($materialCode)) {
                    $usageQuery->where('materialable_id', $materialCode);
                }

                $usages = $usageQuery->get();

                foreach ($usages as $item) {
                    $furnaceId = $chargingFurnaceById[$item->charging_head_id] ?? null;
                    $transactionDate = $furnaceId ? ($furnaceDateById[$furnaceId] ?? null) : null;
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

        $adjustQuery = MaterialAdjustAce::with('materialable')->orderBy('transaction_date');

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
