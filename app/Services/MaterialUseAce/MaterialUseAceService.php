<?php

namespace App\Services\MaterialUseAce;

use App\Repositories\MaterialUseAce\MaterialUseAceReposirotyInterface;
use Illuminate\Support\Facades\Auth;

class MaterialUseAceService
{
    protected $repository;

    public function __construct(MaterialUseAceReposirotyInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getDetail($id, $anchor)
    {
        return $this->repository->getDetail($id, $anchor);
    }
    public function getRawMat($id)
    {
        return $this->repository->getRawMat($id);
    }
    public function getAdditiveMat($id)
    {
        return $this->repository->getAdditiveMat($id);
    }
    public function getKwh($id)
    {
        return $this->repository->getKwh($id);
    }
    public function getTempTapping($id)
    {
        return $this->repository->getTempTapping($id);
    }
    // public function getCharging($id)
    // {
    //     return $this->repository->getCharging($id);
    // }
    // public function saveCharging($data)
    // {
    //     return $this->repository->saveCharging($data);
    // }
    // public function saveChargingHead($data)
    // {
    //     return $this->repository->saveChargingHead($data);
    // }
    public function saveRawMat($data)
    {
        $newData = array_map(function ($item) {
            unset($item['material_name']);
            return $item;
        }, $data);
        return $this->repository->saveRawMat($newData);
    }
    public function saveUpdateRawMat($data)
    {
        // dd($data);
        $user = Auth::user()->usr;
        $now = now();
        $update = array_map(function ($item) use ($now, $user) {
            unset($item['material_name']);
            unset($item['materialable']);
            $item['updated_by'] = $user;
            $item['updated_at'] = $now;
            return $item;
        }, $data);
        return $this->repository->saveUpdateRawMat($update);
    }
    public function saveAdditiveMat($data)
    {
        return $this->repository->saveAdditiveMat($data);
    }
    public function saveUpdateAdditiveMat($data)
    {
        return $this->repository->saveUpdateAdditiveMat($data);
    }
    public function saveKwh($data)
    {
        return $this->repository->saveKwh($data);
    }
    public function UpdateKwh($data)
    {
        return $this->repository->UpdateKwh($data);
    }
    public function saveTemptTapping($data)
    {
        return $this->repository->saveTemptTapping($data);
    }
    public function updateTemptTapping($data)
    {
        return $this->repository->updateTemptTapping($data);
    }
}
