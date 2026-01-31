<?php

namespace App\Models\Jsh\MaterialUse;

use App\Enums\EnumTypeAdditive;
use App\Enums\EnumTypeMat;
use App\Models\BaseModelJsh;

class MaterialUsageJsh extends BaseModelJsh
{

    protected $table = 'tb_material_usage_jsh';

    protected $casts = [
        'type_additive' => EnumTypeAdditive::class,
        'type' => EnumTypeMat::class,
    ];

    protected $fillable = [
        'charging_head_id',
        'material_id',
        'weight',
        'type',
        'type_additive',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function charging()
    {
        return $this->belongsTo(MaterialUsageJsh::class, 'id', 'charging_head_id');
    }
}
