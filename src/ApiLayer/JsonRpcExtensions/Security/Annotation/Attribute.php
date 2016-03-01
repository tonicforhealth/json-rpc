<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Attribute
{
    /**
     * Attribute name.
     *
     * @var string
     */
    public $name;

    /**
     * Path to attribute value at request object property.
     *
     * @var string
     */
    public $valueAt;
}