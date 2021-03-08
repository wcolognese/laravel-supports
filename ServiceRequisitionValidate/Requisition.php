<?php

namespace WColognese\LaravelSupports\ServiceRequisitionValidate;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use WColognese\LaravelSupports\ServiceRequisitionValidate\Contracts\IRequisition;
use ArrayAccess;
use InvalidArgumentException;

abstract class Requisition implements IRequisition, ArrayAccess
{
    protected $_data = array();

    /* @var \Illuminate\Contracts\Validation\Validator */
    protected $_validator = null;

    public function setData($data)
    {
        if($data instanceof Request)
            $data = $data->all();

        if( ! is_array($data))
            throw new InvalidArgumentException('$data must be array or \Illuminate\Http\Request');

        // Seta apenas os campos validos ou esperados
        $this->_data = $this->getOnlyValidatedData($data);

        // Remove o validador ao substituir
        // os dados a serem validados
        $this->_validator = null;
    }

    public function getData(): array
    {
        return $this->_data;
    }

    public function isValid(): bool
    {
        return ! $this->getValidator()->fails();
    }

    public function getValidator(): Validator
    {
        if( ! isset($this->_validator))
            $this->_validator = $this->makeValidator();

        return $this->_validator;
    }

    protected function getOnlyValidatedKeys(): array
    {
        return array_keys($this->rules());
    }

    protected function getOnlyValidatedData($data): array
    {
        return Arr::only($data, $this->getOnlyValidatedKeys());
    }

    protected function makeValidator(): Validator
    {
        return \Illuminate\Support\Facades\Validator::make(
            $this->getData(),
            $this->rules(),
            method_exists($this, 'validatorMessages') ? $this->validatorMessages() : [],
            method_exists($this, 'validatorCustomAttributes') ? $this->validatorCustomAttributes() : []
        );
    }

    public static function make(array $data): self
    {
        $requisition = new static();

        $requisition->setData($data);

        return $requisition;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
            $this->_data[] = $value;
        else
            $this->_data[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    public function offsetGet($offset)
    {
        return$this->offsetExists($offset) ? $this->_data[$offset] : null;
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function toArray()
    {
        return $this->_data;
    }
}