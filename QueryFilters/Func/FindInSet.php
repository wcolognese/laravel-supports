<?php

namespace WColognese\LaravelSupports\QueryFilters\Func;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use WColognese\LaravelSupports\QueryFilters\Filter;

class FindInSet extends Filter
{
    /**
     * @param EloquentBuilder|QueryBuilder $builder
     * @return EloquentBuilder|QueryBuilder
     */
    protected function apply($builder)
    {
        $value = $this->getValue();

        if(is_array($value))
        {
            $builder->where(function ($query) use($value) {
                foreach($value as $val)
                    $this->applyWhereCondition($query, $val, 'or');
            });
        }
        else
            $this->applyWhereCondition($builder, $value);

        return $builder;
    }

    protected function applyWhereCondition(Builder &$builder, $value, string $boolean = 'and'): Builder
    {
        if(env('APP_ENV') == 'testing')
            return $builder->whereRaw("(`" . $this->columnName() . "` LIKE '%$value,%' OR `" . $this->columnName() . "` LIKE '%,$value,' OR `" . $this->columnName() . "` LIKE '%$value')", [], $boolean);

        return $builder->whereRaw('FIND_IN_SET(?, `' . $this->columnName() . '`)', $value, $boolean);
    }
}