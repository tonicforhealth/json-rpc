<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Method;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvokerInterface;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Annotation\Attribute;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Exception\AccessDeniedException;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\GuardInterface;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\UserProviderInterface;
use Tonic\Component\Reflection\ReflectionFunctionFactory;

/**
 * Decorator for method invoker, which allows to secure method invokation.
 */
class SecurableMethodInvoker implements MethodInvokerInterface
{
    /**
     * @var MethodInvokerInterface
     */
    private $methodInvoker;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var GuardInterface
     */
    private $guard;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @param MethodInvokerInterface $methodInvoker
     * @param UserProviderInterface  $userProvider
     * @param GuardInterface         $guard
     * @param Reader                 $annotationReader
     * @param PropertyAccessor       $propertyAccessor
     */
    public function __construct(
        MethodInvokerInterface $methodInvoker,
        UserProviderInterface $userProvider,
        GuardInterface $guard,
        Reader $annotationReader,
        PropertyAccessor $propertyAccessor
    ) {
        $this->methodInvoker = $methodInvoker;
        $this->userProvider = $userProvider;
        $this->guard = $guard;
        $this->annotationReader = $annotationReader;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(callable $callable, $requestObject)
    {
        /** @var Attribute $attributeAnnotation */
        $attributeAnnotation = $this->annotationReader->getMethodAnnotation(ReflectionFunctionFactory::createReflectionMethodFromCallable($callable), Attribute::class);
        $attributeName = $attributeAnnotation->name;
        $attributeValue = $this->propertyAccessor->getValue($requestObject, $attributeAnnotation->valueAt);

        $userId = $this->userProvider->getUserId();
        if ((!is_array($attributeValue)) && (!$this->guard->isGranted($userId, $attributeName, $attributeValue))) {
            throw new AccessDeniedException();
        }

        if (is_array($attributeValue)) {
            $attributeValue = $this->guard->filterGranted($userId, $attributeName, $attributeValue);
            $this->propertyAccessor->setValue($requestObject, $attributeAnnotation->valueAt, $attributeValue);
        }

        return $this->methodInvoker->invoke($callable, $requestObject);
    }
}
