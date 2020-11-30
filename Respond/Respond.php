<?php

namespace WColognese\LaravelSupports\Respond;

use Illuminate\Contracts\Support\Arrayable;
use Closure;
use Throwable;
use Illuminate\Http\JsonResponse;

class Respond implements Arrayable
{
    protected $status = true;
    protected $message;
    protected $messageSuccessDefault = 'Sucesso ao processar sua solicitação';
    protected $messageErrorDefault = 'Erro ao processar a solicitação';
    protected $data;
    protected $statusCode = 200;
    protected $errors = [];

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setMessageOnSuccess(string $message): self
    {
        $this->messageSuccessDefault = $message;

        return $this;
    }

    public function setMessageOnFailure(string $message): self
    {
        $this->messageErrorDefault = $message;

        return $this;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;

        return $this;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setSuccess(): self
    {
        return $this->setMessage($this->message ?? $this->messageSuccessDefault)
                        ->setStatus(true)
                            ->setStatusCode(200);
    }

    public function setError(array $errors = [], int $statusCode = 500): self
    {
        $this->errors = $errors;

        return $this->setMessage($this->message ?? $this->messageErrorDefault)
                        ->setStatus(false)
                            ->setStatusCode($statusCode);
    }

    public function setErrorByThrowable(Throwable $e): self
    {
        $errors = method_exists($e, 'errors') ? $e->errors() : [];

        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : (isset($e->status) ? $e->status : 520);  // HTTP 520 Unknown Error

        return $this->setMessage($e->getMessage())->setError($errors, $statusCode);
    }

    public function do(Closure $closure = null, ?string $resultKey = ''): JsonResponse
    {
        if($closure)
        {
            $result = $closure($this);

            if(is_not_null($resultKey))
            {
                if( ! empty($resultKey))
                    $result = [$resultKey => $result];

                $this->setData($result);

                if(is_null($this->message))
                    $this->setMessage($this->status ? $this->messageSuccessDefault : $this->messageErrorDefault);
            }
        }

        return new JsonResponse($this->toArray(), $this->statusCode);
    }

    public function doIf($boolean): JsonResponse
    {
        return ($boolean ? $this->setSuccess() : $this->setError())->do(null);
    }

    public function toArray(): array
    {
        return [
            'status'        => $this->status,
            'message'       => $this->message,
            'data'          => $this->data,
            'errors'        => $this->errors
        ];
    }
}