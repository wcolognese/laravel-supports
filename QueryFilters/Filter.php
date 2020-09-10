<?php

namespace WColognese\LaravelSupports\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Filter
{
    protected $ignoreEmpty  = TRUE;

    protected abstract function apply(Builder $builder): Builder;

    public function handle($request, Closure $next)
    {
        if ( ! $this->canApply())
        {
            return $next($request);
        }

        $builder = $next($request);

        return $this->apply($builder);
    }

    protected function filterName(): string
    {
        return Str::snake(class_basename($this));
    }

    protected function columnName(): string
    {
        return isset($this->columnName) ? $this->columnName : $this->filterName();
    }

    protected function getValue()
    {
        if( ! ($value = get_query_filters_request($this->columnName())))
            $value = request($this->columnName());

        return $value;
    }

    protected function canApply(): bool
    {
        $value = $this->getValue();

        return $value || ($this->ignoreEmpty === FALSE && $value == '');
    }
}