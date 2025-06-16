<?php

namespace App\Enums\Subscription;

use App\Enums\Concerns\EnumToArray;

enum TaxType: string
{
    use EnumToArray;

    case PERCENTAGE="percent";
    case FIXED="fixed";

}
