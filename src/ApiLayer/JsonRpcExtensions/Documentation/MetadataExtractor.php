<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation;

use Doctrine\Common\Annotations\PhpParser;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;
use Tonic\Component\Reflection\TypeResolver;

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
        foreach ($methodCollection as $methodName => $callable) {
            $reflectionFunction = $this->createReflectionFunction($callable);

            $requestObjectClass = $this->determineRequestObjectClass($reflectionFunction);
            $responseObjectClass = $this->determineResponseObjectClass($reflectionFunction);

            $docBlock = new DocBlock($reflectionFunction);

            $metadata[] = [
                'method' => $methodName,
                'description' => $docBlock->getShortDescription(),
                'parameters' => $this->extractParameters($requestObjectClass),
                'returns' => $this->extractParameters($responseObjectClass)
            ];
        }

        return $metadata;
    }

    /**
     * @param string $class
     * @param array $processed
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
            if (($pos = strpos($type, '[')) !== false) {
                // if type is collection of types
                $isCollection = true;
                $type = substr($type, 0, $pos);
            }

            $docBlock = new DocBlock($reflectionProperty);

            $parameter = [
                'name' => $reflectionProperty->getName(),
                'description' => $docBlock->getShortDescription(),
                'type' => $type . ($isCollection ? '[]' : '')
            ];
            if (class_exists($type)) {
                $parameter = array_merge($parameter, [
                    'type' => 'object' . ($isCollection ? '[]' : ''),
                    'properties' => $this->extractParameters($type, $processed)
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

        return $reflectionParameter->getClass()->getName();
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