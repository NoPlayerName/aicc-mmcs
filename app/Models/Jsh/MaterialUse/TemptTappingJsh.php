<?php

namespace App\Models\Jsh\MaterialUse;

use App\Enums\EnumTypeTapping;
use App\Models\BaseModelJsh;

class TemptTappingJsh extends BaseModelJsh
{
    protected $table = 'tb_tempt_tapping';

    protected $casts = [
        'type_tapping' => EnumTypeTapping::class,
    ];

    protected $fillable = [
        'charging_head_id',
        'temperatur',
        'type_tapping',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function charging()
    {
        return $this->belongsTo(ChargingHead::class, 'id', 'charging_head_id');
    }
}
