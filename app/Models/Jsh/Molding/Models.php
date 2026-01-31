<?php

namespace App\Models\Jsh\Molding;

use App\Models\Jsh\ProdPlan;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    protected $connection = 'molding';

    protected $table = 'model';
    protected $primaryKey = 'id';

    public function planProd()
    {
        return $this->hasMany(ProdPlan::class, 'id', 'model_id');
    }
}
