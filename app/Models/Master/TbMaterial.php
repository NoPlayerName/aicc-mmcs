<?php

namespace App\Models\Master;

use App\Models\Jsh\MaterialUse\MaterialUsageJsh;
use Illuminate\Database\Eloquent\Model;

class TbMaterial extends Model
{
    protected $connection = 'master';
    protected $table = 'tb_material_erp';
    protected $primaryKey = 'material_code';
    protected $keyType = 'string';
    public $incrementing = false;

    public function materialUse()
    {
        return $this->hasMany(MaterialUsageJsh::class, 'material_id', 'material_code');
    }
}
