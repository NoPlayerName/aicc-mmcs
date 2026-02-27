<?php

namespace App\Models\Ace\MaterialUse;

use App\Models\BaseModelJsh;
use App\Models\Master\TbMaterial;

class Inoculant extends BaseModelJsh
{
    protected $table = 'tb_inoculant_trx';
    protected $fillable = [
        'leadle_head_id',
        'material_id',
        'weight',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function ladleTfHead()
    {
        return $this->belongsTo(LadleTfHead::class, 'leadle_head_id', 'id');
    }
    public function material()
    {
        return $this->belongsTo(TbMaterial::class, 'material_id', 'material_code');
    }

    public function materialable()
    {
        return $this->belongsTo(TbMaterial::class, 'material_id', 'material_code');
    }
}
