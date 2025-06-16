<?php

namespace App\Models\Concerns;

use App\Filters\BaseFilter;

trait HasFilter
{
    public function scopeFilter($query, BaseFilter $filter): \Illuminate\Database\Eloquent\Builder
    {
        return $filter->apply($query);
    }
}