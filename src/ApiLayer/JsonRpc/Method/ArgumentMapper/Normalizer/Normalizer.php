<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag\VarTag;

class Normalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object)
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($className, array $data = [])
    {
        // @todo check for denormalization errors

        $reflectionClass = new \ReflectionClass($className);
        $instance = $reflectionClass->newInstanceWithoutConstructor();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $type = $this->detectType($reflectionProperty);

            $instance->{$propertyName} = class_exists($type)
                ? $this->denormalize($type, $data[$propertyName])
                : $data[$propertyName]
            ;
        }

        return $instance;
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return string|null
     */
    public function detectType(\ReflectionProperty $reflectionProperty)
    {
        // @todo parse use block to work with FQCN

        $docBlock = new DocBlock($reflectionProperty->getDocComment());
        $tags = $docBlock->getTagsByName('var');
        if (count($tags) == 0 ) {
            return null;
        }

        /** @var VarTag $typeTag */
        $typeTag = reset($tags);

        return $typeTag->getType();
    }
}