<?php

namespace App\Repositories\MaterialUseAce;

use App\Enums\EnumTypeMat;
use App\Models\Ace\MaterialUse\ChargingHeadAce;
use App\Models\Ace\MaterialUse\FurnaceHeadAce;
use App\Models\Ace\MaterialUse\Inoculant;
use App\Models\Ace\MaterialUse\KwhAce;
use App\Models\Ace\MaterialUse\LadleTfHead;
use App\Models\Ace\MaterialUse\MaterialUsageAce;
use App\Models\Ace\MaterialUse\TemptTappingAce;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MaterialUseAceReposiroty implements MaterialUseAceReposirotyInterface
{

    public function getDetail($id, $anchor)
    {
        $data = ChargingHeadAce::with(['rawMatUse', 'additMatUse', 'Kwh', 'TemptTapping'])
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
        $data = MaterialUsageAce::with('materialable')->select('charging_head_id', 'materialable_id', 'materialable_type', 'weight', 'type', 'created_by', 'created_at')->where('charging_head_id', $data)
            ->where('type', EnumTypeMat::RawMaterial->value)->get()->map(function ($item) {
                $item->material_name = $item->materialable?->material_name ?? '-';
                return $item->makeHidden('material');
            });
        // dd($data);
        return $data;
    }
    public function getAdditiveMat($id)
    {
        $data = MaterialUsageAce::with('materialable')->select('charging_head_id', 'materialable_id', 'materialable_type', 'weight', 'type', 'type_additive', 'created_by', 'created_at')->where('charging_head_id', $id)
            ->where('type', EnumTypeMat::Additive->value)->get()->map(function ($item) {
                $item->type_additive_text = $item->type_additive?->text() ?? '-';
                $item->material_name = $item->materialable?->material_name ?? '-';
                return $item->makeHidden('material');
            });
        return $data;
    }
    public function getKwh($id)
    {
        $data = KwhAce::select('charging_head_id', 'charge_time', 'kwh_start_charge', 'kwh_ok_charge', 'power')->where('charging_head_id', $id)
            ->first();
        return $data;
    }
    public function getTempTapping($id)
    {
        $data = TemptTappingAce::select('charging_head_id', 'temperatur', 'type_tapping', 'created_by', 'created_at')->where('charging_head_id', $id)
            ->get()->map(function ($item) {
                $item->type_tapping_text = $item->type_tapping?->text() ?? '-';
                return $item;
            });
        return $data;
    }
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
    public function saveAdditiveMat($data)
    {
        $dataToSave = collect($data)
            ->map(fn($item) => collect($item)->forget('type_additive_text')->toArray())
            ->toArray();
        // dd($data);
        DB::beginTransaction();
        try {

            MaterialUsageAce::insert($dataToSave);
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
        DB::beginTransaction();
        try {
            MaterialUsageAce::where('charging_head_id', $data[0]['charging_head_id'])->where('type', EnumTypeMat::Additive->value)->delete();
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
    public function saveKwh($data)
    {
        DB::beginTransaction();
        try {
            KwhAce::insert($data);
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
            KwhAce::where('charging_head_id', $data['charging_head_id'])->update($data);
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
        DB::beginTransaction();
        try {
            TemptTappingAce::insert($data);
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
        DB::beginTransaction();
        try {

            TemptTappingAce::where('charging_head_id', $data[0]['charging_head_id'])->delete();
            TemptTappingAce::insert($data);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update tempt tapping fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return false;
        }
    }

    public function saveLadle($data)
    {

        try {
            DB::beginTransaction();
            $query = LadleTfHead::create($data);
            $ladleHeadId = $query->id;
            DB::commit();
            return [
                'ladleHeadId' => $ladleHeadId,
                'status' => true,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save ladle fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return [
                'status' => false,
            ];
        }
    }
    public function saveLadleMat($data)
    {
        try {
            DB::beginTransaction();
            Inoculant::insert($data);
            DB::commit();
            return [
                'status' => true,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Save inoculant mat fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return [
                'status' => false,
            ];
        }
    }

    public function updateLadleTransfer($id, $ladleHead, $ladleMat)
    {
        DB::beginTransaction();
        try {
            LadleTfHead::where('id', $id)->update($ladleHead);

            Inoculant::where('leadle_head_id', $id)->delete();
            if (!empty($ladleMat)) {
                Inoculant::insert($ladleMat);
            }

            DB::commit();
            return [
                'status' => true,
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update ladle transfer fail', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return [
                'status' => false,
            ];
        }
    }

    public function getLadleTransfer($date, $shift)
    {
        // dd($date, $shift);

        $data = LadleTfHead::with(['product', 'furnace', 'inoculants.materialable'])
            ->whereHas('furnace', function ($query) use ($date, $shift) {
                $query->where('date', $date)->where('shift', $shift);
            })
            ->get()
            ->map(function ($item) {
                $inoculants = $item->inoculants ?? collect();
                $inoculants->map(function ($inoculant) {
                    $inoculant->material_name = $inoculant->materialable?->material_name ?? $inoculant->material_id ?? '-';
                    return $inoculant;
                });

                return $item;
            });
        return $data;


        // $query = FurnaceHeadAce::with(['ladleTfHead.inoculants.materialable', 'ladleTfHead.product'])->where('date', $date)->where('shift', $shift)->get()->map(function ($item) {
        //     $inoculants = $item->inoculants ?? collect();
        //     $inoculants->map(function ($inoculant) {
        //         $inoculant->material_name = $inoculant->materialable?->material_name ?? $inoculant->material_id ?? '-';
        //         return $inoculant;
        //     });

        //     return $item;
        // });;
        // return $query;
    }
}
