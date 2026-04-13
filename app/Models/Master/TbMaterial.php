<?php

namespace App\Models\Master;

use App\Models\Jsh\MaterialUse\MaterialUsageJsh;
use Illuminate\Database\Eloquent\Model;

class TbMaterial extends Model
{
    protected $connection = 'master';
    protected $table = 'v_mat_material_use';
    protected $primaryKey = 'material_code';
    protected $keyType = 'string';
    public $incrementing = false;

    public function materialUse()
    {
        return $this->morphMany(MaterialUsageJsh::class, 'materialable');
    }
}
