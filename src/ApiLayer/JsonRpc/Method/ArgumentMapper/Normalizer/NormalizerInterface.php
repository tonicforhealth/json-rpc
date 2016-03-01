<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer;

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
     * @param array $data
     *
     * @return object
     */
    public function denormalize($className, array $data = []);
}