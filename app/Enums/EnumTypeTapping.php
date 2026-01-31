<?php

namespace App\Enums;

use App\Enums\EnumTraits;

enum EnumTypeTapping: int
{
    use EnumTraits;
    case Sample1 = 1;
    case Sample2 = 2;
    case Tapping1 = 3;
    case Tapping2 = 4;
    case Tapping3 = 5;
    public function text()
    {
        return match ($this) {
            self::Sample1 => 'Temperatur Sample 1',
            self::Sample2 => 'Temperatur Sample 2',
            self::Tapping1 => 'Temperatur Tapping 1',
            self::Tapping2 => 'Temperatur Tapping 2',
            self::Tapping3 => 'Temperatur Tapping 3',
        };
    }
}
