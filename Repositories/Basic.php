<?php

namespace WColognese\LaravelSupports\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

abstract class Basic
{
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

    protected function all(): Collection
    {
        return $this->getEntityInstance()->all();
    }

    protected function delete($key): bool
    {
        if($model = $this->byKey($key))
            return $model->delete();

        return FALSE;
    }

    protected function qbApplyFilters(array $filters, Builder $builder = null): Builder
    {
        return app(Pipeline::class)
                    ->send($builder ?? $this->getEntityInstance()->query())
                        ->through($filters)
                            ->thenReturn();
    }
}