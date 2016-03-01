<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method\Loader;

use Doctrine\Common\Annotations\Reader;
use Tonic\Component\ApiLayer\JsonRpc\Annotation\Method;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;

class MutableAnnotationLoader implements LoaderInterface
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var object[]
     */
    private $services = [];

    /**
     * Constructor.
     *
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param object $service
     *
     * @return MutableAnnotationLoader
     */
    public function add($service)
    {
        if (!is_object($service)) {
            throw new \InvalidArgumentException('Supports only objects');
        }

        $this->services[] = $service;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $methods = [];
        foreach ($this->services as $service) {
            $methods = array_merge($methods, $this->extractCallables($service));
        }

        return new MethodCollection($methods);
    }

    /**
     * @param object $service
     *
     * @return callable[]
     */
    private function extractCallables($service)
    {
        $methods = [];
        $reflectionClass = new \ReflectionClass($service);
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            /** @var Method $methodAnnotation */
            $methodAnnotation = $this->annotationReader->getMethodAnnotation($reflectionMethod, Method::class);
            if ($methodAnnotation instanceof Method) {
                $methods[$methodAnnotation->name] = [$service, $reflectionMethod->getName()];
            }
        }

        return $methods;
    }
}
