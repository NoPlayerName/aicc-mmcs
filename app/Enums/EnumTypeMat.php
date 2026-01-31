<?php

namespace App\Enums;

use App\Enums\EnumTraits;

enum EnumTypeMat: int
{
    use EnumTraits;
    case RawMaterial = 1;
    case Additive = 2;
}
