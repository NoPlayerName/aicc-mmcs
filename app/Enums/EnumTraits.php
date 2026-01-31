<?php

namespace App\Enums;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait EnumTraits
{
    public function title()
    {
        return Str::of($this->name)->headline()->toString();
    }

    public static function includes($enum)
    {
        return [];
    }

    public static function all(): Collection
    {
        return collect(self::cases())->map(
            fn(self $enum) => (object) [
                'name' => $enum->name,
                'value' => $enum->value,
                'title' => $enum->title(),
                'text'  => method_exists($enum, 'text') ? $enum->text() : $enum->title(),
                ...self::includes($enum),
            ]
        );
    }
}
