<?php

namespace App\Models\Jsh\MaterialUse;

use App\Enums\EnumTypeMat;
use App\Models\BaseModelJsh;

class ChargingHead extends BaseModelJsh
{
    protected $connection = "material-use";
    protected $table = "tb_charging_head_jsh";

    protected $fillable = [
        'plan_id_anchor',
        'charging',
        'lot',
        'created_by',
        'created_at',
        'updated_at',
    ];


    public function rawMatUse()
    {
        return $this->hasMany(MaterialUsageJsh::class, 'charging_head_id', 'id')->where('type', EnumTypeMat::RawMaterial->value);
    }
    public function additMatUse()
    {
        return $this->hasMany(MaterialUsageJsh::class, 'charging_head_id', 'id')->where('type', EnumTypeMat::Additive->value);
    }
    public function Kwh()
    {
        return $this->hasMany(KwhJsh::class, 'charging_head_id', 'id');
    }
    public function TemptTapping()
    {
        return $this->hasMany(TemptTappingJsh::class, 'charging_head_id', 'id');
    }

    public function furnaceHead()
    {
        return $this->belongsTo(FurnaceHead::class, 'plan_id_anchor', 'id');
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
