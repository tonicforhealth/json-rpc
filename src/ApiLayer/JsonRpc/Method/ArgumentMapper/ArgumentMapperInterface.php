<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper;

/**
 * Maps arguments to request object.
 */
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
