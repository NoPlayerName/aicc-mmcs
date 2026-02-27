<?php

namespace App\Repositories\PlanProductionAce;

use App\Models\Ace\MaterialUse\ChargingHeadAce;
use App\Models\Ace\MaterialUse\FurnaceHeadAce;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class PlanProdRepositoryAce implements PlanProdRepositoryAceInterface
{

    public function generateFurnace($request)
    {
        try {
            DB::beginTransaction();

            $furnaceHead = FurnaceHeadAce::create($request);

            DB::commit();

            return $furnaceHead;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating furnace head: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request,
            ]);
            throw $e;
        }
    }

    public function getFurnaceHead($date, $shift)
    {
        return FurnaceHeadAce::query()->with([
            'chargings.product',
            'chargings.rawMatUse',
            'chargings.additMatUse',
        ])
            ->whereDate('date', $date)
            ->where('shift', $shift)
            ->whereBetween('furnace', [6, 9])
            ->orderBy('furnace')
            ->get()->map(function ($furnace) {
                $furnace->total_raw_material = $furnace->chargings->sum(function ($charging) {
                    return $charging->rawMatUse->sum('weight');
                });
                $furnace->total_additive = $furnace->chargings->sum(function ($charging) {
                    return $charging->additMatUse->sum('weight');
                });
                return $furnace;
            });
    }
    public function getNextChargingNumber($planIdAnchor): int
    {
        // Ganti ModelCharging dengan model detail/charging yang sudah Anda pakai saat ini
        $lastCharging = ChargingHeadAce::query()
            ->where('plan_id_anchor', $planIdAnchor)
            ->max('charging');

        return ($lastCharging === null ? 0 : (int) $lastCharging) + 1;
    }
    public function generateCharging($data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $lastCharging = ChargingHeadAce::query()
                    ->where('plan_id_anchor', $data['plan_id_anchor'])
                    ->lockForUpdate()
                    ->max('charging');

                $data['charging'] = ($lastCharging === null ? 0 : (int) $lastCharging) + 1;

                $query = ChargingHeadAce::query()->create($data);
                return [
                    'status' => true,
                    'message' => "Charging {$query} berhasil ditambahkan.",
                ];
            });
        } catch (\Exception $e) {

            Log::error('Error generating charging head: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $data,
            ]);
            return [
                'status' => false,
                'message' => "Gagal menambahkan charging.",
            ];
        }
    }

    public function saveSelection($id, $productId, $lotIds)
    {

        try {
            DB::beginTransaction();
            $chargingHead = ChargingHeadAce::find($id);
            if (!$chargingHead) {
                Log::warning("Charging head with ID {$id} not found.");
                return [
                    'status' => false,
                    'message' => "Charging head not found.",
                ];
            }
            $chargingHead->update([
                'model_id' => $productId,
                'lot' => $lotIds,
            ]);
            DB::commit();
            return [
                'status' => true,
                'message' => "Selection saved successfully.",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving selection: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'model_id' => $productId,
                'lot' => $lotIds,
            ]);
            return [
                'status' => false,
                'message' => "Gagal menyimpan selection.",
            ];
        }
    }

    public function getFurnace($date, $shift)
    {
        return FurnaceHeadAce::query()->with([
            'chargings.product',
        ])
            ->whereDate('date', $date)
            ->where('shift', $shift)
            ->whereBetween('furnace', [6, 9])
            ->orderBy('furnace')
            ->get();
    }
}
