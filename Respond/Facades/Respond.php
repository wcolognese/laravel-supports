<?php

namespace WColognese\LaravelSupports\Respond\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self setMessage(string $message)
 * @method static self setMessageOnSuccess(string $message)
 * @method static self setMessageOnFailure(string $message)
 * @method static self setStatus(bool $status)
 * @method static self setData($data)
 * @method static self setSuccess()
 * @method static self setError(array $errors = [], int $statusCode = 500)
 * @method static self setErrorByThrowable(\Throwable $e)
 * @method static self setDefaultSuccessMsg()
 * @method static self setDefaultErrorMsg()
 * @method static self setStatusCode(int $code)
 * @method static \Illuminate\Http\JsonResponse do(\Closure $closure = null, ?string $resultKey = '')
 * @method static \Illuminate\Http\JsonResponse doIf(bool $boolean)
 * @method static array toArray()
 */
class Respond extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'respond';
    }
}