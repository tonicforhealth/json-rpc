<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

/**
 * Invokes method with specified parameters.
 */
interface MethodInvokerInterface
{
    /**
     * @param callable $callable
     * @param array|object $requestObject
     *
     * @return array|object
     */
    public function invoke(callable $callable, $requestObject);
}
