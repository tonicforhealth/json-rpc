<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper;

interface ArgumentMapperInterface
{
    /**
     * Maps arguments to simple request object.
     *
     * @param callable $callable
     * @param array    $arguments
     *
     * @return object
     */
    public function mapToObject(callable $callable, array $arguments);
}
