<?php

namespace App\Models\Master;

use App\Models\Jsh\MaterialUse\MaterialUsageJsh;
use Illuminate\Database\Eloquent\Model;

class TbMaterialTrial extends Model
{
    protected $connection = 'master';
    protected $table = 'tb_material_trial';
    protected $primaryKey = 'material_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['material_code', 'material_name', 'unit', 'is_active'];

    public function materialUse()
    {
        return $this->morphMany(MaterialUsageJsh::class, 'materialable');
    }
}
