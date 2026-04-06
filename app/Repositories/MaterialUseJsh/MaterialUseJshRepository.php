<?php

namespace App\Repositories\MaterialUseJsh;

use App\Enums\EnumTypeMat;
use App\Models\Jsh\MaterialUse\ChargingHead;
use App\Models\Jsh\MaterialUse\FurnaceHead;
use App\Models\Jsh\MaterialUse\KwhJsh;
use App\Models\Jsh\MaterialUse\MaterialUsageJsh;
use App\Models\Jsh\MaterialUse\TemptTappingJsh;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MaterialUseJshRepository implements MaterialUseJshRepositoryInterface
{


    public function getDetail($id, $anchor)
    {
        $data = ChargingHead::with(['rawMatUse', 'additMatUse', 'Kwh', 'TemptTapping'])
            ->where('id', $id)
            ->where('plan_id_anchor', $anchor)
            ->first();
        if (!$data) return null;

        return (object) [
            'id'             => $data->id,
            'plan_id_anchor' => $data->plan_id_anchor,
            'charging'       => $data->charging,
            'rawMat'         => $data->rawMatUse->map(function ($item) {
                $item->material_name = $item->materialable?->material_name ?? '-';
                return $item;
            }),
            'additive'       => $data->additMatUse->map(function ($item) {
                $item->material_name = $item->materialable?->material_name ?? '-';
                $item->type_additive_text = $item->type_additive?->text() ?? '-';
                return $item;
            }),
            'kwh'            => $data->Kwh,
            'tapping'        => $data->TemptTapping->map(function ($item) {
                $item->type_tapping_text = $item->type_tapping?->text() ?? '-';
                return $item;
            }),
        ];
    }

    public function getRawMat($data)
    {
        $data = MaterialUsageJsh::with('materialable')->select('charging_head_id', 'materialable_id', 'materialable_type', 'weight', 'type', 'created_by', 'created_at')->where('charging_head_id', $data)
            ->where('type', EnumTypeMat::RawMaterial->value)->get()->map(function ($item) {
                $item->material_name = $item->materialable?->material_name ?? '-';
                return $item->makeHidden('materialable');
            });
        // dd($data);
        return $data;
    }
    public function getAdditiveMat($data)
    {
        $data = MaterialUsageJsh::with('materialable')->select('charging_head_id', 'materialable_id', 'materialable_type', 'weight', 'type', 'type_additive', 'created_by', 'created_at')->where('charging_head_id', $data)
            ->where('type', EnumTypeMat::Additive->value)->get()->map(function ($item) {
                $item->type_additive_text = $item->type_additive?->text() ?? '-';
                $item->material_name = $item->materialable?->material_name ?? '-';
                return $item->makeHidden('materialable');
            });
        return $data;
    }
    public function getKwh($data)
    {
        $data = KwhJsh::select('charging_head_id', 'charge_time', 'kwh_start_charge', 'kwh_ok_charge', 'power')->where('charging_head_id', $data)
            ->first();
        return $data;
    }

    public function getTempTapping($data)
    {
        $data = TemptTappingJsh::select('charging_head_id', 'temperatur', 'type_tapping', 'created_by', 'created_at')->where('charging_head_id', $data)
            ->get()->map(function ($item) {
                $item->type_tapping_text = $item->type_tapping?->text() ?? '-';
                return $item;
            });
        return $data;
    }


    public function getCharging($id)
    {
        $data =  ChargingHead::findOrFail($id);
        return $data;
    }

    public function saveChargingHead($data)
    {

        try {
            $dataReturn =  ChargingHead::create($data);
            return $dataReturn;
        } catch (\Throwable $th) {
            Log::error('Save charging fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function saveCharging($data)
    {
        try {
            MaterialUsageJsh::insert($data);
            return true;
        } catch (\Throwable $th) {
            Log::error('Save charging no fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function saveRawMat($data)
    {
        // dd($data);
        DB::beginTransaction();
        try {

            MaterialUsageJsh::insert($data);
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
        // dd($data);
        DB::beginTransaction();
        try {
            MaterialUsageJsh::where('charging_head_id', $data[0]['charging_head_id'])->where('type', EnumTypeMat::RawMaterial->value)->delete();
            MaterialUsageJsh::insert($data);
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

    public function saveAdditiveMat($data)
    {
        $dataToSave = collect($data)
            ->map(fn($item) => collect($item)->forget('type_additive_text')->toArray())
            ->toArray();
        // dd($data);
        DB::beginTransaction();
        try {

            MaterialUsageJsh::insert($dataToSave);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save additive fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
    public function saveUpdateAdditiveMat($data)
    {
        // dd($data);
        DB::beginTransaction();
        try {
            MaterialUsageJsh::where('charging_head_id', $data[0]['charging_head_id'])->where('type', EnumTypeMat::Additive->value)->delete();
            MaterialUsageJsh::insert($data);
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

    public function saveKwh($data)
    {
        DB::beginTransaction();
        try {
            KwhJsh::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save kwh fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
    public function UpdateKwh($data)
    {
        DB::beginTransaction();
        try {
            KwhJsh::where('charging_head_id', $data['charging_head_id'])->update($data);
            // KwhJsh::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save kwh fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
    public function saveTemptTapping($data)
    {
        // dd($data);
        DB::beginTransaction();
        try {
            TemptTappingJsh::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save tempt tapping fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
    public function updateTemptTapping($data)
    {
        // dd($data);
        DB::beginTransaction();
        try {

            TemptTappingJsh::where('charging_head_id', $data[0]['charging_head_id'])->delete();
            TemptTappingJsh::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save tempt tapping fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }
}
