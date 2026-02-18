<?php

namespace App\Models\Jsh\MaterialUse;

use App\Enums\EnumTypeAdditive;
use App\Enums\EnumTypeMat;
use App\Models\BaseModelJsh;
use App\Models\Master\TbMaterial;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MaterialUsageJsh extends BaseModelJsh
{

    protected $table = 'tb_material_usage_jsh';

    protected $casts = [
        'type_additive' => EnumTypeAdditive::class,
        'type' => EnumTypeMat::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'charging_head_id',
        'materialable_id',   // UBAH: dari material_id
        'materialable_type',
        'weight',
        'type',
        'type_additive',
        'created_by',
        'created_at',
        'updated_at',
    ];


    public function charging()
    {
        return $this->belongsTo(ChargingHead::class, 'id', 'charging_head_id');
    }
    public function materialable(): MorphTo
    {
        return $this->morphTo();
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
