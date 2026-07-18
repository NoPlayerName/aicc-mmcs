<?php

namespace App\Models\Jsh\MaterialUse;

use App\Models\Jsh\Molding\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class FurnaceHead extends Model
{
    use Loggable;
    protected $connection = "material-use";
    protected $table = "tb_furnace_head_jsh";

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
        return $this->hasMany(ChargingHead::class, 'plan_id_anchor', 'id');
    }
    public function chargingHeads()
    {
        return $this->hasMany(ChargingHead::class, 'plan_id_anchor', 'id');
    }

    public function models()
    {
        return $this->belongsTo(Models::class, 'model_id', 'id');
    }
}
