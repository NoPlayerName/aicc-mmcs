<?php

namespace App\Services\MaterialUseJsh;

use App\Repositories\MaterialUseJsh\MaterialUseJshRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class MaterialUseJshService
{

    protected $materialUse;
    public function __construct(MaterialUseJshRepositoryInterface $materialUse)
    {
        $this->materialUse = $materialUse;
    }

    public function getDetail($id, $anchor)
    {
        $data = $this->materialUse->getDetail($id, $anchor);
        return $data;
    }

    public function getRawMat($id)
    {
        $data = $this->materialUse->getRawMat($id);
        return $data;
    }

    public function getAdditiveMat($id)
    {
        $data = $this->materialUse->getAdditiveMat($id);
        return $data;
    }
    public function getKwh($id)
    {
        $data = $this->materialUse->getKwh($id);
        return $data;
    }
    public function getTempTapping($id)
    {
        $data = $this->materialUse->getTempTapping($id);
        return $data;
    }


    public function getChargeById($id)
    {
        $data = $this->materialUse->getCharging($id);
        return $data;
    }
    public function saveCharging($data)
    {
        $data = $this->materialUse->saveCharging($data);
        return $data;
    }
    public function saveDesc($data)
    {
        $data = $this->materialUse->saveDesc($data);
        return $data;
    }

    public function saveChargingHead($data)
    {
        $data = $this->materialUse->saveChargingHead($data);
        return $data;
    }
    public function updateChargingHead($data)
    {
        if (!is_array($data) || !isset($data['id'])) {
            return false;
        }

        $newData = $data;
        $newData['updated_by'] = $data['created_by'] ?? Auth::user()->usr;
        unset($newData['created_by']);
        $data = $this->materialUse->updateChargingHead($newData);
        return $data;
    }

    public function saveRawMat($data)
    {
        $newData = array_map(function ($item) {
            unset($item['material_name']);
            unset($item['materialable']);
            return $item;
        }, $data);
        $save = $this->materialUse->saveRawMat($newData);
        return $save;
    }
    public function saveUpdateRawMat($data)
    {
        $user = Auth::user()->usr;
        $now = now();
        $update = array_map(function ($item) use ($now, $user) {
            unset($item['material_name']);
            unset($item['materialable']);
            $item['updated_by'] = $user;
            $item['updated_at'] = $now;
            return $item;
        }, $data);
        // dd($update);
        $save = $this->materialUse->saveUpdateRawMat($update);
        return $save;
    }
    public function saveAdditiveMat($data)
    {
        $newData = array_map(function ($item) {
            unset($item['type_additive_text']);
            unset($item['material_name']);
            unset($item['materialable']);
            return $item;
        }, $data);
        $save = $this->materialUse->saveAdditiveMat($newData);
        return $save;
    }
    public function saveUpdateAdditiveMat($data)
    {
        $user = Auth::user()->usr;
        $now = now();
        $update = array_map(function ($item) use ($now, $user) {
            unset($item['type_additive_text']);
            unset($item['material_name']);
            unset($item['materialable']);
            $item['updated_by'] = $user;
            $item['updated_at'] = $now;
            return $item;
        }, $data);
        $save = $this->materialUse->saveUpdateAdditiveMat($update);
        return $save;
    }
    public function saveKwh($data)
    {
        $user = Auth::user()->usr;

        $newData = array_merge($data, [
            'created_by' => $user,
            'created_at' => now(),
        ]);

        $save = $this->materialUse->saveKwh($newData);
        return $save;
    }
    public function UpdateKwh($data)
    {
        $user = Auth::user()->usr;

        $newData = array_merge($data, [
            'updated_by' => $user,
            'updated_at' => now(),
        ]);

        $save = $this->materialUse->UpdateKwh($newData);
        return $save;
    }
    public function saveTemptTapping($data)
    {

        unset($data['type_tapping_text']);
        $newData = collect($data)->map(function ($item) {
            // 1. Ubah array jadi collection agar bisa pakai forget()
            $row = collect($item)->forget('type_tapping_text');

            return $row->all();
        })->all();
        $save = $this->materialUse->saveTemptTapping($newData);
        return $save;
    }

    public function createManualCharging($furnace, $date, $shift, $charging = null)
    {
        // dd($furnace, $date, $shift, $charging);
        // Create furnace head if not exists
        $furnaceHeadService = app(FurnaceHeadService::class);
        $existingFurnace = $furnaceHeadService->getFurnaceHeadByDateShift($date, $shift)->where('furnace', $furnace)->first();
        // dd($existingFurnace);
        // if (!$existingFurnace) {
        //     $existingFurnace = $furnaceHeadService->createFurnaceHead($furnace, $date, $shift);
        // }

        if (!$existingFurnace) {
            return false;
        }

        // Determine charging number
        if ($charging === null) {
            $lastCharging = $existingFurnace->chargings()->max('charging') ?? 0;
            $charging = $lastCharging + 1;
        }

        // Create charging head
        $chargingData = [
            'plan_id_anchor' => (string) $existingFurnace->id, // Use furnace head id as anchor
            'charging' => $charging,
            'created_by' => Auth::user()->usr,
        ];

        $chargingHead = $this->saveChargingHead($chargingData);

        return $chargingHead ? ['status' => true, 'message' => 'Charging di tambahkan'] : false;
    }
    public function updateTemptTapping($data)
    {
        $user = Auth::user()->usr;
        unset($data['type_tapping_text']);
        $newData = collect($data)->map(function ($item) use ($user) {
            // 1. Ubah array jadi collection agar bisa pakai forget()
            $row = collect($item)->forget('type_tapping_text');

            // 2. Tambahkan kolom baru
            $row->put('updated_at', now());
            $row->put('updated_by', $user);

            return $row->all();
        })->all();
        $save = $this->materialUse->updateTemptTapping($newData);
        return $save;
    }
}
