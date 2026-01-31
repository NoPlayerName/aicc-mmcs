<?php

namespace App\Enums;

use App\Enums\EnumTraits;

enum EnumTypeAdditive: int
{
    use EnumTraits;
    case PraAdjust = 1;
    case Adjustment = 2;
    public function text()
    {
        return match ($this) {
            self::PraAdjust => 'Pra Adjust',
            self::Adjustment => 'Adjust',
        };
    }
}
