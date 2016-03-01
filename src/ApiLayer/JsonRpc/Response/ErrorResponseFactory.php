<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidCallableArgumentsException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidMethodParametersException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\MethodNotFoundException;
use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\InvalidRequestException;
use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\ParseException;
use Tonic\Component\ApiLayer\JsonRpc\Exception\Exception as ApplicationDefinedException;

class ErrorResponseFactory
{
    /**
     * @var bool
     */
    private $exposeInternalExceptions = false;

    /**
     * Constructor.
     *
     * @param bool|false $exposeInternalExceptions
     */
    public function __construct($exposeInternalExceptions = false)
    {
        $this->exposeInternalExceptions = $exposeInternalExceptions;
    }

    /**
     * @return ErrorResponseFactory
     */
    public function exposeInternalExceptions()
    {
        $this->exposeInternalExceptions = true;

        return $this;
    }

    /**
     * @param \Exception $exception
     * @param string|null $id
     *
     * @return ErrorResponse
     */
    public function createForException(\Exception $exception, $id = null)
    {
        switch (true) {
            case $exception instanceof ParseException:
                return ErrorResponse::parseError($exception->getMessage() ?: 'Parse error');
            case $exception instanceof InvalidRequestException:
                return ErrorResponse::invalidRequestError($exception->getMessage() ?: 'Invalid request');
            case $exception instanceof MethodNotFoundException:
                return ErrorResponse::methodNotFoundError($id, $exception->getMessage() ?: 'Method not found');
            case $exception instanceof InvalidCallableArgumentsException:
            case $exception instanceof InvalidMethodParametersException:
                return ErrorResponse::invalidParamsError($id, $exception->getMessage() ?: 'Invalid params');
            case $exception instanceof ApplicationDefinedException:
                return ErrorResponse::applicationDefinedError($id, $exception->getMessage(), $exception->getCode());
            case $exception instanceof \Exception:
            default:
                if ($this->exposeInternalExceptions) {
                    return new ErrorResponse($id, new Error($exception->getMessage(), $exception->getCode()));
                }

                return ErrorResponse::applicationError($id);
        }
    }
}