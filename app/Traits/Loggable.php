<?php

namespace App\Traits;

use App\Observers\ActivityObserver;

trait Loggable
{
    public static function bootLoggable()
    {
        static::observe(ActivityObserver::class);
    }
}
