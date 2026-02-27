<?php

namespace App\Models\Ace\MaterialUse;

use App\Models\Ace\KanbanNew\ProductKanban;
use App\Models\BaseModelJsh;

class LadleTfHead extends BaseModelJsh
{

    protected $table = 'tb_ladle_tf_head';

    protected $fillable = [
        'furnace_id',
        'lot',
        'product_id',
        'weighing_status',
        'conveyor_drop_status',
        'ladle_drop_status',
        'treatment_duration_check',
        'molten_weight',
        'ladle_molten_temp',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function furnace()
    {
        return $this->belongsTo(FurnaceHeadAce::class, 'furnace_id', 'id');
    }
    public function inoculants()
    {
        return $this->hasMany(Inoculant::class, 'leadle_head_id', 'id');
    }

    public function inoculant()
    {
        return $this->hasMany(Inoculant::class, 'leadle_head_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(ProductKanban::class, 'product_id', 'id');
    }
}
