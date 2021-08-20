<?php

namespace WColognese\LaravelSupports\QueryFilters\Operator;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use WColognese\LaravelSupports\QueryFilters\Filter;

class In extends Filter
{
    /**
     * @param EloquentBuilder|QueryBuilder $builder
     * @return EloquentBuilder|QueryBuilder
     */
    protected function apply($builder)
    {
        $value = $this->getValue();

        if( ! is_array($value) )
            $value = [$value];

        return $builder->whereIn($this->columnName(), $value);
    }
}