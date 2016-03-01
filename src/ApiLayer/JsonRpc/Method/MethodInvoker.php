<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

class MethodInvoker implements MethodInvokerInterface
{
    /**
     * {@inheritdoc}
     */
    public function invoke(callable $callable, $requestObject)
    {
        return call_user_func_array($callable, [$requestObject]);
    }
}
