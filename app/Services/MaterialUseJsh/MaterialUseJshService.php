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

    public function saveChargingHead($data)
    {
        $data = $this->materialUse->saveChargingHead($data);
        return $data;
    }

    public function saveRawMat($data)
    {
        $save = $this->materialUse->saveRawMat($data);
        return $save;
    }
    public function saveAdditiveMat($data)
    {
        $save = $this->materialUse->saveAdditiveMat($data);
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
    public function saveTemptTapping($data)
    {
        $user = Auth::user()->usr;
        unset($data['typeTappingText']);
        $newData = collect($data)->map(function ($item) use ($user) {
            // 1. Ubah array jadi collection agar bisa pakai forget()
            $row = collect($item)->forget('typeTappingText');

            // 2. Tambahkan kolom baru
            $row->put('created_at', now());
            $row->put('created_by', $user);

            return $row->all();
        })->all();
        $save = $this->materialUse->saveTemptTapping($newData);
        return $save;
    }
}
