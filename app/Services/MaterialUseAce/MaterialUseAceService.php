<?php

namespace App\Services\MaterialUseAce;

use App\Repositories\MaterialUseAce\MaterialUseAceReposirotyInterface;
use Carbon\Carbon;
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
            unset($item['materialable']);
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
        $newData = array_map(function ($item) {
            unset($item['type_additive_text']);
            unset($item['material_name']);
            unset($item['materialable']);
            return $item;
        }, $data);
        return $this->repository->saveAdditiveMat($newData);
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
        return $this->repository->saveUpdateAdditiveMat($update);
    }
    public function saveKwh($data)
    {
        $user = Auth::user()->usr;

        $newData = array_merge($data, [
            'created_by' => $user,
            'created_at' => now(),
        ]);
        return $this->repository->saveKwh($newData);
    }
    public function UpdateKwh($data)
    {
        $user = Auth::user()->usr;

        $newData = array_merge($data, [
            'updated_by' => $user,
            'updated_at' => now(),
        ]);
        return $this->repository->UpdateKwh($newData);
    }
    public function saveTemptTapping($data)
    {
        unset($data['type_tapping_text']);
        $newData = collect($data)->map(function ($item) {
            // 1. Ubah array jadi collection agar bisa pakai forget()
            $row = collect($item)->forget('type_tapping_text');

            return $row->all();
        })->all();
        return $this->repository->saveTemptTapping($newData);
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
        return $this->repository->updateTemptTapping($newData);
    }

    public function saveLadleTransfer($data)
    {

        $query = $this->repository->saveLadle($data['ladlehead']);
        if ($query['status']) {
            $data['ladleMat'] = array_map(function ($item) use ($query) {
                $item['leadle_head_id'] = $query['ladleHeadId'];
                unset($item['material_name']);
                return $item;
            }, $data['ladleMat']);
            $querys = $this->repository->saveLadleMat($data['ladleMat']);
            return $querys;
        } else {
            return [
                'status' => false,
            ];
        }
    }

    public function updateLadleTransfer($id, $data)
    {
        $ladleMat = array_map(function ($item) use ($id) {
            unset($item['id']);
            unset($item['material_name']);
            $item['leadle_head_id'] = $id;
            return $item;
        }, $data['ladleMat']);

        return $this->repository->updateLadleTransfer($id, $data['ladlehead'], $ladleMat);
    }

    public function getLadleTransfer($date = null, $shiftParam = null)
    {

        $currentDateTime = now();
        $hour = $currentDateTime->hour;
        $shift = ($hour >= 7 && $hour < 20) ? 'D' : 'N';

        $productionDate = $currentDateTime->copy();
        if ($shift === 'N' && $hour < 7) {
            $productionDate->subDay();
        }
        if (!empty($date)) {
            $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
            // dd($date, $productionDate, $shiftParam ?? $shift);
        }

        $dateValue = ($parsedDate ?? $productionDate)->format('Y-m-d');
        return $this->repository->getLadleTransfer(
            $dateValue,
            $shiftParam ?? $shift
        );
    }

    public function getLadleTransferReport(?string $startDate, ?string $endDate, ?string $shift = null)
    {
        return $this->repository->getLadleTransferReport($startDate, $endDate, $shift);
    }
}
