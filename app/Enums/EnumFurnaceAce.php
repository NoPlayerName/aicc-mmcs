<?php

namespace App\Enums;

use App\Enums\EnumTraits;

enum EnumFurnaceAce: int
{
    use EnumTraits;

    case Furnace6 = 6;
    case Furnace7 = 7;
    case Furnace8 = 8;
    case Furnace9 = 9;
    public function text()
    {
        return match ($this) {
            self::Furnace6 => 'Furnace 6',
            self::Furnace7 => 'Furnace 7',
            self::Furnace8 => 'Furnace 8',
            self::Furnace9 => 'Furnace 9',
        };
    }
}
