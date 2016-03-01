<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

interface MethodInvokerInterface
{
    /**
     * @param callable $callable
     * @param object $requestObject
     *
     * @return array|object
     */
    public function invoke(callable $callable, $requestObject);
}