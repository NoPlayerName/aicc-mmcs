<?php

namespace App\Models\Ace\MaterialUse;

use App\Enums\EnumTypeAdditive;
use App\Enums\EnumTypeMat;
use App\Models\BaseModelJsh;
use App\Models\Master\TbMaterial;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MaterialUsageAce extends BaseModelJsh
{

    protected $table = 'tb_material_usage_ace';

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
        return $this->belongsTo(ChargingHeadAce::class, 'id', 'charging_head_id');
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
