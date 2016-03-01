<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

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