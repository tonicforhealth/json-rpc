<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Method
{
    /**
     * @var string
     */
    public $name;
}
