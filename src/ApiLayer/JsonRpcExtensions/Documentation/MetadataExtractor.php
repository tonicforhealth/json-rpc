<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation;

use phpDocumentor\Reflection\DocBlock;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;
use Tonic\Component\Reflection\ReflectionFunctionFactory;
use Tonic\Component\Reflection\TypeResolver;

/**
 * Extracts metadata from method collection.
 */
class MetadataExtractor
{
    /**
     * @var TypeResolver
     */
    private $typeResolver;

    /**
     * @param TypeResolver $typeResolver
     */
    public function __construct(TypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * @param MethodCollection $methodCollection
     *
     * @return array
     */
    public function extract(MethodCollection $methodCollection)
    {
        $metadata = [];
        /** @var callable $callable */
        foreach ($methodCollection as $methodName => $callable) {
            $reflectionFunction = ReflectionFunctionFactory::createFromCallable($callable);

            $requestObjectClass = $this->determineRequestObjectClass($reflectionFunction);
            $responseObjectClass = $this->determineResponseObjectClass($reflectionFunction);

            $docBlock = new DocBlock($reflectionFunction);

            $metadata[] = [
                'method' => $methodName,
                'description' => $docBlock->getShortDescription(),
                'parameters' => $this->extractParameters($requestObjectClass),
                'returns' => $this->extractParameters($responseObjectClass),
            ];
        }

        return $metadata;
    }

    /**
     * @param string $class
     * @param array  $processed
     *
     * @return array
     */
    private function extractParameters($class, $processed = [])
    {
        if (isset($processed[$class])) {
            return [];
        }
        $processed[$class] = true;

        $reflectionClass = new \ReflectionClass($class);
        $parameters = [];
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $type = $this->determinePropertyType($reflectionProperty);

            $isCollection = false;
            if ($extractedType = $this->typeResolver->extractTypeFromCollectionType($type)) {
                $isCollection = true;
                $type = $extractedType;
            }

            $docBlock = new DocBlock($reflectionProperty);

            $parameter = [
                'name' => $reflectionProperty->getName(),
                'description' => $docBlock->getShortDescription(),
                'type' => $type.($isCollection ? '[]' : ''),
            ];
            if (class_exists($type)) {
                $parameter = array_merge($parameter, [
                    'type' => 'object'.($isCollection ? '[]' : ''),
                    'properties' => $this->extractParameters($type, $processed),
                ]);
            }

            $parameters[] = $parameter;
        }

        return $parameters;
    }

    /**
     * @param \ReflectionFunctionAbstract $reflectionFunction
     *
     * @return string
     */
    private function determineRequestObjectClass(\ReflectionFunctionAbstract $reflectionFunction)
    {
        $reflectionParameters = $reflectionFunction->getParameters();
        /** @var \ReflectionParameter $reflectionParameter */
        $reflectionParameter = reset($reflectionParameters);

        return $reflectionParameter->getClass()->name;
    }

    /**
     * @param \ReflectionFunctionAbstract $reflectionFunction
     *
     * @return string
     */
    private function determineResponseObjectClass(\ReflectionFunctionAbstract $reflectionFunction)
    {
        return $this->typeResolver->resolveFunctionReturnType($reflectionFunction);
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return null|string
     */
    private function determinePropertyType(\ReflectionProperty $reflectionProperty)
    {
        return $this->typeResolver->resolvePropertyType($reflectionProperty);
    }
}
