<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper;

use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer\NormalizerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidCallableArgumentsException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidMethodParametersException;
use Tonic\Component\Reflection\ReflectionFunctionFactory;

/**
 * Maps arguments to request object.
 */
class ArgumentMapper implements ArgumentMapperInterface
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * Constructor.
     *
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function mapToObject(callable $callable, array $arguments)
    {
        if ((count($arguments) > 0) && $this->isIndexedArray($arguments)) {
            throw new InvalidCallableArgumentsException('Can not map indexed arrays');
        }

        $reflectionFunction = ReflectionFunctionFactory::createFromCallable($callable);
        if ($reflectionFunction->getNumberOfRequiredParameters() > 1) {
            throw new InvalidMethodParametersException('Could not map to more than one parameter');
        }

        $reflectionParameters = $reflectionFunction->getParameters();
        /** @var \ReflectionParameter $targetReflectionParameter */
        $targetReflectionParameter = reset($reflectionParameters);
        if (null === $targetReflectionParameter->getClass()) {
            throw new InvalidMethodParametersException('Method parameter should have type definition');
        }

        $mapped = $this->normalizer->denormalize($targetReflectionParameter->getClass()->name, $arguments);

        return $mapped;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    private function isIndexedArray(array $array)
    {
        return array_values($array) == $array;
    }
}
