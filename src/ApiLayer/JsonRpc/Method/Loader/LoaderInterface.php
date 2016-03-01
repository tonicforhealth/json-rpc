<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\Loader;

use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;

/**
 * Responsible for loading method collection.
 */
interface LoaderInterface
{
    /**
     * @return MethodCollection
     */
    public function load();
}
