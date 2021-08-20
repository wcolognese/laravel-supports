<?php

namespace WColognese\LaravelSupports\QueryFilters\Operator;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use WColognese\LaravelSupports\QueryFilters\Filter;

class Equal extends Filter
{
    /**
     * @param EloquentBuilder|QueryBuilder $builder
     * @return EloquentBuilder|QueryBuilder
     */
    protected function apply($builder)
    {
        return $builder->where($this->columnName(), $this->getValue());
    }
}