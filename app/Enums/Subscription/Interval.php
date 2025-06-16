<?php

namespace App\Enums\Subscription;

use App\Enums\Concerns\EnumToArray;

enum Interval: string
{
    use EnumToArray;

    case YEAR = 'year';
    case MONTH = 'month';
    case DAY = 'day';

    public function label()
    {
        return match ($this) {
            Interval::YEAR => "Year",
            Interval::MONTH => "Month",
            Interval::DAY => "Day",
        };

    }


}
