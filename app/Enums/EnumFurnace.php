<?php

namespace App\Enums;

use App\Enums\EnumTraits;

enum EnumFurnace: int
{
    use EnumTraits;
    case Furnace1 = 1;
    case Furnace2 = 2;
    case Furnace3 = 3;
    case Furnace4 = 4;
    case Furnace5 = 5;
    public function text()
    {
        return match ($this) {
            self::Furnace1 => 'Furnace 1',
            self::Furnace2 => 'Furnace 2',
            self::Furnace3 => 'Furnace 3',
            self::Furnace4 => 'Furnace 4',
            self::Furnace5 => 'Furnace 5',
        };
    }
}
