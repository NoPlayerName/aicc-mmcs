<?php

namespace App\Models\Jsh\MaterialUse;

use App\Models\BaseModelJsh;

class KwhJsh extends BaseModelJsh
{
    protected $table = 'tb_kwh_jsh';
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected $fillable = [
        'charging_head_id',
        'charge_time',
        'kwh_start_charge',
        'kwh_ok_charge',
        'power',
        'created_by',
        'created_at',
        'created_by',
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
