<?php

namespace WColognese\LaravelSupports\ServiceRequisitionValidate\Contracts;

use Illuminate\Contracts\Validation\Validator;

interface IRequisition
{
    public static function make(array $data);

    public function getData(): array;

    public function setData($data);

    public function rules(): array;

    public function getValidator(): Validator;
}