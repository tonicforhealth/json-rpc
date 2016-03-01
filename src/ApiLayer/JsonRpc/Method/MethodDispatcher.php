<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\ArgumentMapperInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\MethodNotFoundException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Loader\LoaderInterface;

class MethodDispatcher implements MethodDispatcherInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var ArgumentMapperInterface
     */
    private $argumentMapper;

    /**
     * @var MethodInvokerInterface
     */
    private $methodInvoker;

    /**
     * @var MethodCollection|null
     */
    private $methodCollection = null;

    /**
     * Constructor.
     *
     * @param LoaderInterface $loader
     * @param ArgumentMapperInterface $argumentMapper
     * @param MethodInvokerInterface $methodInvoker
     */
    public function __construct(
        LoaderInterface $loader,
        ArgumentMapperInterface $argumentMapper,
        MethodInvokerInterface $methodInvoker
    )
    {
        $this->loader = $loader;
        $this->argumentMapper = $argumentMapper;
        $this->methodInvoker = $methodInvoker;
    }

    /**
     * @return MethodCollection
     */
    public function getMethodCollection()
    {
        if (null == $this->methodCollection) {
            $this->methodCollection = $this->loader->load();
        }

        return $this->methodCollection;
    }

    /**
     * @param string $methodName
     * @param array $arguments
     *
     * @return mixed
     *
     * @throws MethodNotFoundException
     */
    public function dispatch($methodName, array $arguments = [])
    {
        $methodCollection = $this->getMethodCollection();
        if (! $methodCollection->has($methodName)) {
            throw new MethodNotFoundException(sprintf('Method "%s" is not found', $methodName));
        }

        $callable = $methodCollection->get($methodName);

        $requestObject = $this->argumentMapper->mapToObject($callable, $arguments);

        return $this->methodInvoker->invoke($callable, $requestObject);
    }
}
