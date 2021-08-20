<?php

namespace WColognese\LaravelSupports\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use WColognese\LaravelSupports\Useful\PipeApplicables;

abstract class Basic
{
    use PipeApplicables;

    protected $saveForceCreateIfHasKeyValue = false;

    protected abstract function getEntityInstance(): Model;

    protected function __byKey($key): ?Model
    {
        return $this->getEntityInstance()->find($key);
    }

    protected function __save(array $data): ?Model
    {
        $entity = $this->getEntityInstance();

        if( ! empty($data[$this->getEntityKeyName()]))
        {
            $entityFound = $this->__byKey($data[$this->getEntityKeyName()]);

            if( ! is_null($entityFound) || ! $this->saveForceCreateIfHasKeyValue)
                $entity = $entityFound;
        }

        return $entity->fill($data)->save() ? $entity : NULL;
    }

    protected function __updateByKey($key, array $data): ?Model
    {
        $data[$this->getEntityKeyName()] = $key;

        return $this->__save($data);
    }

    protected function getEntityKeyName(): string
    {
        return $this->getEntityInstance()->getKeyName();
    }

    protected function __create(array $data): ?Model
    {
        $model = $this->getEntityInstance();

        if($model->fill($data)->save())
            return $model;

        return NULL;
    }

    protected function __byColumn(string $column, $value): ?Model
    {
        return $this->getEntityInstance()->where($column, $value)->first();
    }

    protected function __allWhere(string $column, $value): Collection
    {
        return $this->getEntityInstance()->where($column, $value)->get();
    }

    protected function __all(): Collection
    {
        return $this->getEntityInstance()->all();
    }

    protected function __delete($key): bool
    {
        if($model = $this->__byKey($key))
            return $model->delete();

        return FALSE;
    }


    /**
     * @param array $filters
     * @param QueryBuilder|EloquentBuilder|null $builder
     * @return mixed
     */
    protected function qbApplyFilters(array $filters, $builder = null)
    {
        return $this->pipeApplicables(
                        $filters,
                $builder ?? $this->getEntityInstance()->query()
            );
    }
}