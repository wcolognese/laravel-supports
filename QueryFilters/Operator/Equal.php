<?php

namespace WColognese\LaravelSupports\QueryFilters\Operator;

use Illuminate\Database\Eloquent\Builder;
use WColognese\LaravelSupports\QueryFilters\Filter;

class Equal extends Filter
{
    protected function apply(Builder $builder): Builder
    {
        return $builder->where($this->columnName(), $this->getValue());
    }
}