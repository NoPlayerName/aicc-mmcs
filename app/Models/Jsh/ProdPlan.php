<?php

namespace App\Models\Jsh;

use App\Models\Jsh\Molding\Models;
use Illuminate\Database\Eloquent\Model;

class ProdPlan extends Model
{
    protected $connection = 'jsh_prod_plan';

    protected $table = 'tb_production_plan';
    protected $primaryKey = 'production_plan_id';
    protected $keyType = 'string';


    public function models()
    {
        return $this->belongsTo(Models::class, 'model_id', 'id');
    }
}
