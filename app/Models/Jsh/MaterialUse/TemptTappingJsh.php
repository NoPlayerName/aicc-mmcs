<?php

namespace App\Models\Jsh\MaterialUse;

use App\Enums\EnumTypeTapping;
use App\Models\BaseModelJsh;

class TemptTappingJsh extends BaseModelJsh
{
    protected $table = 'tb_tempt_tapping';

    protected $casts = [
        'type_tapping' => EnumTypeTapping::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
