<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

/**
 * Simple implementation of invoker.
 * Just invokes method with specified argument and nothing more.
 */
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
