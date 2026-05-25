<?php

namespace App\Repositories\PlanProductionJsh;

use App\Models\Jsh\MaterialUse\ChargingHead;
use App\Models\Jsh\MaterialUse\FurnaceHead;
use App\Models\Jsh\MaterialUse\MaterialUsageJsh;
use App\Models\Jsh\ProdPlan;
use App\Repositories\PlanProductionJsh\PlanProdRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanProdRepository implements PlanProdRepositoryInterface
{
    public function generateData($tanggal, $shift)
    {
        // dd($tanggal, $shift);
        // $tanggal = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        $user = Auth::user()->usr;
        try {
            $plans = ProdPlan::with('models')->where('plan_process_date', $tanggal)
                ->where('shift', $shift)
                ->orderBy('plan_furnace')
                ->get();
            if ($plans->isEmpty()) {
                return collect();
            }
            $planIds = $plans->pluck('production_plan_id');
            $existingInputs = ChargingHead::query()
                ->with([
                    'rawMatUse',   // ->sum('weight')
                    'additMatUse', // ->sum('weight')
                ])->whereIn('plan_id_anchor', $planIds)
                ->select('id', 'plan_id_anchor', 'charging', 'desc')
                ->get()
                ->keyBy('plan_id_anchor');

            $data = $plans
                ->groupBy('plan_furnace')
                ->map(function ($group) use ($existingInputs) {
                    $firstGroup = $group->first();


                    // Sort items by lot and group consecutive lots (max 3 per charging)
                    $sortedItems = $group->sortBy(fn($item) => (int) $item->lot)->values();
                    $itemGroups = [];
                    $currentGroup = [];

                    foreach ($sortedItems as $item) {
                        if (empty($currentGroup)) {
                            $currentGroup[] = $item;
                            continue;
                        }

                        $lastLot = (int) end($currentGroup)->lot;
                        $currentLot = (int) $item->lot;

                        if ($currentLot === $lastLot + 1 && count($currentGroup) < 3) {
                            $currentGroup[] = $item;
                        } else {
                            $itemGroups[] = $currentGroup;
                            $currentGroup = [$item];
                        }
                    }

                    if (!empty($currentGroup)) {
                        $itemGroups[] = $currentGroup;
                    }

                    $chargings = array_map(function ($itemsGroup) use ($existingInputs) {
                        $firstItem = $itemsGroup[0];
                        $anchorId = $firstItem->production_plan_id;
                        $charge = $existingInputs->get($anchorId);
                        $totalRaw = (float) ($charge?->rawMatUse?->sum('weight') ?? 0);
                        $totalAdditive = (float) ($charge?->additMatUse?->sum('weight') ?? 0);

                        return [
                            'production_plan_id' => $firstItem->production_plan_id,
                            'chargingHeadId' => $charge ? $charge->id : null,
                            'charging' => $charge ? $charge->charging : null,
                            'lot'         => collect($itemsGroup)->pluck('lot')->implode(', '),
                            'model_id'       => $firstItem->models?->model,
                            'desc'  => $charge ? $charge->desc : null,
                            'total_raw_material'  => $totalRaw,
                            'total_additive'      => $totalAdditive,
                        ];
                    }, $itemGroups);

                    if ($firstGroup->plan_furnace === 4) {
                        $furnaceM = FurnaceHead::where('furnace', $firstGroup->plan_furnace)
                            ->where('date', $firstGroup->plan_process_date)
                            ->where('shift', $firstGroup->shift)
                            ->first();
                        $chargings = array_merge($chargings, $furnaceM ? $furnaceM->chargings->map(function ($charging) {
                            return [
                                'production_plan_id' => $charging->plan_id_anchor,
                                'chargingHeadId' => $charging->id,
                                'charging' => $charging->charging ?? '-',
                                'lot' => $charging->lot ?? '-',
                                'model_id' => $charging->model_id ?? '-',
                                'desc'  => $charging->desc ?? null,
                                'total_raw_material' => (float) $charging->rawMatUse->sum('weight'),
                                'total_additive' => (float) $charging->additMatUse->sum('weight'),
                            ];
                        })->toArray() : []);
                    }

                    if ($firstGroup->plan_furnace === 5) {
                        $furnaceN = FurnaceHead::where('furnace', $firstGroup->plan_furnace)
                            ->where('date', $firstGroup->plan_process_date)
                            ->where('shift', $firstGroup->shift)
                            ->first();
                        $chargings = array_merge($chargings, $furnaceN ? $furnaceN->chargings->map(function ($charging) {
                            return [
                                'production_plan_id' => $charging->plan_id_anchor,
                                'chargingHeadId' => $charging->id,
                                'charging' => $charging->charging ?? '-',
                                'lot' => $charging->lot ?? '-',
                                'model_id' => $charging->model_id ?? '-',
                                'desc'  => $charging->desc ?? null,
                                'total_raw_material' => (float) $charging->rawMatUse->sum('weight'),
                                'total_additive' => (float) $charging->additMatUse->sum('weight'),
                            ];
                        })->toArray() : []);
                    }

                    return [
                        'plan_furnace'  =>  $firstGroup->plan_furnace ?? '-',
                        'plan_process_date'  =>  $firstGroup->plan_process_date ?? '-',
                        'shift'  =>  $firstGroup->shift ?? '-',
                        'model_id' =>  $firstGroup->models?->model ?? '-',
                        'chargings' => collect($chargings), // 🔥 charging DI DALAM prodplan
                        'total_raw_material'  => (float) collect($chargings)->sum('total_raw_material'),
                        'total_additive'      => (float) collect($chargings)->sum('total_additive'),
                    ];
                })
                ->values();
            // dd($data);
            return $data;
        } catch (\Throwable $th) {
            Log::error('Generate Furnace Charging Failed', [
                'date'  => $tanggal,
                'shift' => $shift,
                'user'  => $user,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return false;
        }
    }
}

// <?php

// namespace App\Repositories\PlanProductionJsh;

// use App\Models\Jsh\MaterialUse\ChargingHead;
// use App\Models\Jsh\MaterialUse\FurnaceHead;
// use App\Models\Jsh\ProdPlan;
// use App\Repositories\PlanProductionJsh\PlanProdRepositoryInterface;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;

// class PlanProdRepository implements PlanProdRepositoryInterface
// {
//     public function generateData($tanggal, $shift)
//     {
//         // $tanggal = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
//         $user = Auth::user()->usr;
//         try {
//             $query = ProdPlan::where('plan_process_date', $tanggal)
//                 ->where('shift', $shift)
//                 ->orderBy('plan_furnace')
//                 ->get();

//             if ($query->isEmpty()) {
//                 return;
//             }

//             DB::transaction(function () use ($query, $tanggal, $shift, $user) {
//                 $query->groupBy('plan_furnace')->each(function ($furnacePlans, $furnace) use ($tanggal, $shift, $user) {
//                     $furnaceHeader = FurnaceHead::firstOrCreate(
//                         [
//                             'furnace' => $furnace,
//                             'shift' => $shift,
//                             'date' => $tanggal,
//                         ],
//                     );

//                     $rows = [];

//                     $furnacePlans->groupBy('model_id')
//                         ->each(function ($modelPlans, $modelId) use (&$furnaceHeader, $user, &$rows) {
//                             $modelPlans
//                                 ->pluck('lot')
//                                 ->chunk(3)
//                                 ->each(function ($lot) use (&$modelId, $furnaceHeader, $user, &$rows) {
//                                     $rows[] = [
//                                         'furnace_head_id' => $furnaceHeader->id,
//                                         'lot' => $lot->implode(','),
//                                         'model_id' => $modelId,
//                                         'status' => 0,
//                                         'created_by' => $user,
//                                         'created_at' => now(),
//                                     ];
//                                 });
//                         });
//                     if (! empty($rows)) {
//                         ChargingHead::insert($rows); // 🚀 bulk insert
//                     }
//                 });
//             });

//             return true;
//         } catch (\Throwable $th) {
//             Log::error('Generate Furnace Charging Failed', [
//                 'date'  => $tanggal,
//                 'shift' => $shift,
//                 'user'  => $user,
//                 'error' => $th->getMessage(),
//                 'trace' => $th->getTraceAsString(),
//             ]);

//             return false;
//         }
        
//     }
// }
