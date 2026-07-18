<?php

namespace App\Models;


class ActivityLog extends BaseModelJsh
{

    protected $table = 'tb_activity_logs';
    protected $guarded = ['id'];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
