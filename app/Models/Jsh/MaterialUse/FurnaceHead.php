<?php

namespace App\Models\Jsh\MaterialUse;

use Illuminate\Database\Eloquent\Model;

class FurnaceHead extends Model
{
    protected $connection = "material-use";
    protected $table = "tb_furnace_head_jsh";

    protected $fillable = [
        'furnace',
        'date',
        'shift',
        'created_by',
        'created_at',
        'updated-at',
    ];

    public function chargings()
    {
        return $this->hasMany(ChargingHead::class, 'plan_id_anchor', 'id');
    }
}
