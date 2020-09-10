<?php

namespace WColognese\LaravelSupports\QueryFilters\Func;

use Illuminate\Database\Eloquent\Builder;
use WColognese\LaravelSupports\QueryFilters\Filter;

class FindInSet extends Filter
{
    protected function apply(Builder $builder): Builder
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