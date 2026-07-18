<?php

namespace App\Models\Jsh\Molding;

use App\Models\Jsh\MaterialUse\ChargingHead;
use App\Models\Jsh\ProdPlan;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    protected $connection = 'molding';

    protected $table = 'model';
    protected $primaryKey = 'id';

    public function chargingHead()
    {
        return $this->hasMany(ChargingHead::class, 'model_id', 'id');
    }
    public function planProd()
    {
        return $this->hasMany(ProdPlan::class, 'id', 'model_id');
    }
}
