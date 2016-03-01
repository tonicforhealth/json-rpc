<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Exception;

use Tonic\Component\ApiLayer\JsonRpc\Exception\Exception;

class AccessDeniedException extends Exception
{
    const CODE = 3;

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message ?: 'Access denied', $code ?: self::CODE, $previous);
    }
}