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
            $planIds = $plans->pluck('production_plan_id');
            $existingInputs = ChargingHead::whereIn('plan_id_anchor', $planIds)
                ->select('id', 'plan_id_anchor', 'charging', 'status')
                ->get()
                ->keyBy('plan_id_anchor');
            if ($plans->isEmpty()) {
                return collect();
            }
            $data = $plans
                ->groupBy('plan_furnace')
                ->map(function ($group) use ($existingInputs) {
                    $firstGroup = $group->first();
                    $chargings = $group
                        ->chunk(3)
                        ->map(function ($items, $index) use ($existingInputs) {
                            $firstItem = $items->first();
                            $anchorId = $firstItem->production_plan_id;
                            $charge = $existingInputs->get($anchorId);
                            // dd($charge);
                            return [
                                'production_plan_id' => $firstItem->production_plan_id,
                                'chargingHeadId' => $charge ? $charge->id : null,
                                'charging' => $charge ? $charge->charging : null,
                                'lot'         => $items->pluck('lot')->implode(', '),
                                'model_id'       => $firstItem->models?->model,
                            ];
                        })
                        ->values();

                    return [
                        'plan_furnace'  =>  $firstGroup->plan_furnace,
                        'plan_process_date'  =>  $firstGroup->plan_process_date,
                        'shift'  =>  $firstGroup->shift,
                        'model_id' =>  $firstGroup->models?->model,
                        'chargings' => $chargings, // 🔥 charging DI DALAM prodplan
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
