<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer;

/**
 * Transforms object to array and vice versa.
 */
interface NormalizerInterface
{
    /**
     * @param object $object
     *
     * @return array
     */
    public function normalize($object);

    /**
     * @param string $className
     * @param array  $data
     *
     * @return object
     */
    public function denormalize($className, array $data = []);
}
