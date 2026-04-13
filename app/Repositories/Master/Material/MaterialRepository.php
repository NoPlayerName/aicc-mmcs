<?php

namespace App\Repositories\Master\Material;

use App\Models\Master\TbMaterial;
use App\Models\Master\TbMaterialTrial;

class MaterialRepository implements MaterialRepositoryInterface
{

    public function getAditiveJsh()
    {
        $data = TbMaterial::whereIn('is_for_mmcs', [2, 3])->where('is_material_show', 1)->orderBy('material_name', 'ASC')->get();
        return $data;
    }
    public function getAditive()
    {
        $data = TbMaterial::where('is_for_mmcs', '2')->where('is_material_show', 1)->orderBy('material_name', 'ASC')->get();
        return $data;
    }
    public function getInoculant()
    {
        $data = TbMaterial::where('is_for_mmcs', '3')->where('is_material_show', 1)->orderBy('material_name', 'ASC')->get();
        return $data;
    }

    public function getRawMat()
    {
        $data = TbMaterial::where('is_for_mmcs', '1')->orderBy('material_name', 'ASC')->get();
        return $data;
    }

    public function getRawMatTrial()
    {
        $data =  TbMaterialTrial::where('type_mat', '1')->orderBy('material_name', 'ASC')->get();
        return $data;
    }
    public function getAdditiveMatTrial()
    {
        $data =  TbMaterialTrial::where('type_mat', '2')->orderBy('material_name', 'ASC')->get();
        return $data;
    }
}
