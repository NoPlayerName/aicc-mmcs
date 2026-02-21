<?php

namespace App\Models\Ace\KanbanNew;

use App\Models\Ace\MaterialUse\ChargingHeadAce;
use Illuminate\Database\Eloquent\Model;

class productKanban extends Model
{
    protected $connection = 'kanban';

    protected $table = 'products';

    public function chargingHead()
    {
        return $this->hasMany(ChargingHeadAce::class, 'model_id', 'id');
    }
}
