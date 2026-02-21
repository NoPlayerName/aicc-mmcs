<?php

namespace App\Models\Ace\MaterialUse;

use App\Models\BaseModelJsh;

class FurnaceHeadAce extends BaseModelJsh
{
    protected $connection = "material-use";
    protected $table = "tb_furnace_head_ace";
    public $timestamps = false;

    protected $fillable = [
        'furnace',
        'date',
        'shift',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function chargings()
    {
        return $this->hasMany(ChargingHeadAce::class, 'plan_id_anchor', 'id');
    }
}
