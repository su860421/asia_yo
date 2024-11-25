<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum OrderCurrency: string
{
    use InvokableCases;
    use Names;
    use Values;

    case USD = 'USD';
    case TWD = 'TWD';
}
