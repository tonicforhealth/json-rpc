<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

/**
 * Responsible for method dispatch.
 */
interface MethodDispatcherInterface
{
    /**
     * Resolves and invokes method with specified arguments.
     *
     * @param string $methodName
     * @param array $arguments
     *
     * @return mixed
     */
    public function dispatch($methodName, array $arguments = []);
}