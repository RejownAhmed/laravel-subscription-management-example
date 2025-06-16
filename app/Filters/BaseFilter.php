<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class BaseFilter
{
    /**
     * this is the Base Class of all filters for employees
     * this is used inside the apply() method of filterable models
     * and it simply binds the query variables as filter
     * for example:-> GET employees?search=query
     * And the defined method name/names here must match the query var name which is "search"
     * in this case. The value will be passed down to the method as the first parameter automatically
     *
     */
    public array $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];
    public array $attributes = [];

    protected Builder $builder;

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach (request()->all() as $key => $operator) {
            if (strpos($key, 'operator')) {
                $this->attributes[$key] = $this->filterData($operator);
            }
        }

        foreach (request()->all() as $name => $value) {
            $methodName = Str::camel($name);
            if (method_exists($this, $methodName) && !strpos($name, 'operator')) {
                call_user_func_array([$this, $methodName], array_filter([$this->filterData($value)]));
            }
        }

        return $this->builder;
    }

    public function __get($name)
    {
        $name = Str::snake($name);
        if (strpos($name, 'operator')) {
            return (!empty($this->attributes[$name]) && in_array($this->attributes[$name], $this->operators)) ?
                $this->attributes[$name] :
                '=';
        }
    }

    private function filterData($value)
    {
        if (gettype($value) == "array") return $value;

        return filter_var($value, $this->generateSanitizer($value ?: ''));
    }

    /**
     * @param $data
     * @return false|int
     */
    private function generateSanitizer($data): bool|int
    {
        return filter_id(gettype($data));
    }
}
