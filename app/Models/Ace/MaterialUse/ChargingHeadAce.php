<?php

namespace App\Models\Ace\MaterialUse;

use App\Enums\EnumTypeMat;
use App\Models\Ace\KanbanNew\productKanban;
use App\Models\BaseModelJsh;

class ChargingHeadAce extends BaseModelJsh
{
    protected $table = "tb_charging_head_ace";
    public $timestamps = false;

    protected $fillable = [
        'plan_id_anchor',
        'charging',
        'lot',
        'model_id',
        'created_by',
        'created_at',
        'updated_at',
    ];


    public function rawMatUse()
    {
        return $this->hasMany(MaterialUsageAce::class, 'charging_head_id', 'id')->where('type', EnumTypeMat::RawMaterial->value);
    }
    public function additMatUse()
    {
        return $this->hasMany(MaterialUsageAce::class, 'charging_head_id', 'id')->where('type', EnumTypeMat::Additive->value);
    }
    public function Kwh()
    {
        return $this->hasMany(KwhAce::class, 'charging_head_id', 'id');
    }
    public function TemptTapping()
    {
        return $this->hasMany(TemptTappingAce::class, 'charging_head_id', 'id');
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function product()
    {
        return $this->belongsTo(productKanban::class, 'model_id', 'id');
    }
}
