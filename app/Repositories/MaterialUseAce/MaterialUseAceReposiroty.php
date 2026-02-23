<?php

namespace App\Repositories\MaterialUseAce;

use App\Enums\EnumTypeMat;
use App\Models\Ace\MaterialUse\MaterialUsageAce;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MaterialUseAceReposiroty implements MaterialUseAceReposirotyInterface
{
    public function getDetail($id, $anchor) {}

    public function getRawMat($data)
    {
        $data = MaterialUsageAce::with('materialable')->select('charging_head_id', 'materialable_id', 'materialable_type', 'weight', 'type', 'created_by', 'created_at')->where('charging_head_id', $data)
            ->where('type', EnumTypeMat::RawMaterial->value)->get()->map(function ($item) {
                $item->material_name = $item->materialable?->material_name ?? '-';
                return $item->makeHidden('material');
            });
        // dd($data);
        return $data;
    }
    public function getAdditiveMat($id) {}
    public function getKwh($id) {}
    public function getTempTapping($id) {}
    public function getCharging($id) {}
    public function saveCharging($data) {}
    public function saveChargingHead($data) {}
    public function saveRawMat($data)
    {
        // dd($data);
        DB::beginTransaction();
        try {

            MaterialUsageAce::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save raw mat fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
    public function saveUpdateRawMat($data)
    {
        DB::beginTransaction();
        try {
            MaterialUsageAce::where('charging_head_id', $data[0]['charging_head_id'])->where('type', EnumTypeMat::RawMaterial->value)->delete();
            MaterialUsageAce::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save raw mat fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
    public function saveAdditiveMat($data) {}
    public function saveUpdateAdditiveMat($data) {}
    public function saveKwh($data) {}
    public function UpdateKwh($data) {}
    public function saveTemptTapping($data) {}
    public function updateTemptTapping($data) {}
    // public function getRawMatTrial()
    // {
    //     $data = app(MaterialService::class)->getRawMatTrial();
    //      return $data;
    // }   
    // public function getRawMatNonTrial()
    // {
    //     $data = app(MaterialService::class)->getRawMat();
    //     return $data;
    // }

    // public function getRawMatSelect($is_trial)
    // {
    //     if (!$is_trial) {
    //         $data = app(MaterialService::class)->getRawMat();
    //     } else {
    //         $data = app(MaterialService::class)->getRawMatTrial();
    //     }
    //     return $data;
    // }
}
