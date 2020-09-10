<?php

namespace WColognese\LaravelSupports\QueryFilters\Operator;

use Illuminate\Database\Eloquent\Builder;
use WColognese\LaravelSupports\QueryFilters\Filter;

class In extends Filter
{
    protected function apply(Builder $builder): Builder
    {
        $value = $this->getValue();

        if( ! is_array($value) )
            $value = [$value];

        return $builder->whereIn($this->columnName(), $value);
    }
}