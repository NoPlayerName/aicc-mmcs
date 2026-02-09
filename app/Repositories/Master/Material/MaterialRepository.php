<?php

namespace App\Repositories\Master\Material;

use App\Models\Master\TbMaterial;

class MaterialRepository implements MaterialRepositoryInterface
{

    public function getAditive()
    {
        $data = TbMaterial::where('is_for_mmcs', '2')->get();
        return $data;
    }

    public function getRawMat()
    {
        $data = TbMaterial::where('is_for_mmcs', '1')->get();
        return $data;
    }
}
