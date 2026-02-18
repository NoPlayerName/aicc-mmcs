<?php

namespace App\Repositories\Master\Material;

use App\Models\Master\TbMaterial;
use App\Models\Master\TbMaterialTrial;

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

    public function getRawMatTrial()
    {
        $data =  TbMaterialTrial::where('type_mat', '1')->get();
        return $data;
    }
    public function getAdditiveMatTrial()
    {
        $data =  TbMaterialTrial::where('type_mat', '1')->get();
        return $data;
    }
}
