<?php

namespace App\Models\Ace\MaterialUse;

use App\Models\BaseModelJsh;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LadleTfAdjust extends BaseModelJsh
{
    protected $table = 'tb_ladle_tf_adjust';

    protected $casts = [
        'transaction_date' => 'date',
        'qty_adjust' => 'decimal:3',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'transaction_date',
        'materialable_id',
        'materialable_type',
        'qty_adjust',
        'note',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function materialable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
