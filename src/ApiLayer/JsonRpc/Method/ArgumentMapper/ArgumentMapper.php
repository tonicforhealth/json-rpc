<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper;

use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer\NormalizerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidCallableArgumentsException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidMethodParametersException;

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

        $reflectionFunction = $this->createReflectionFunction($callable);
        if ($reflectionFunction->getNumberOfRequiredParameters() > 1) {
            throw new InvalidMethodParametersException('Could not map to more than one parameter');
        }

        $reflectionParameters = $reflectionFunction->getParameters();
        /** @var \ReflectionParameter $targetReflectionParameter */
        $targetReflectionParameter = reset($reflectionParameters);
        if ($targetReflectionParameter->getClass() == null) {
            throw new InvalidMethodParametersException('Method parameter should have type definition');
        }

        $mapped = $this->normalizer->denormalize($targetReflectionParameter->getClass()->getName(), $arguments);

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

    /**
     * @param callable $callable
     *
     * @return \ReflectionFunctionAbstract
     */
    private function createReflectionFunction(callable $callable)
    {
        return is_array($callable) && (count($callable) == 2)
            ? new \ReflectionMethod($callable[0], $callable[1])
            : new \ReflectionFunction($callable)
        ;
    }
}