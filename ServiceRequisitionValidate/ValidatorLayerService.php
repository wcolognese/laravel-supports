<?php

namespace WColognese\LaravelSupports\ServiceRequisitionValidate;

trait ValidatorLayerService
{
    /* @var ServiceRequisitionValidate */
    protected $validatorLayerService;

    public function setValidatorLayerService(ServiceRequisitionValidate $validatorLayerService)
    {
        $this->validatorLayerService = $validatorLayerService;
    }

    /**
     * @return self
     */
    public function getValidatorLayerService()
    {
        return $this->validatorLayerService;
    }
}