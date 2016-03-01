<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpcExtensions\Security;

use Doctrine\Common\Annotations\Reader;
use PhpSpec\ObjectBehavior;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvokerInterface;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Annotation\Attribute;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Exception\AccessDeniedException;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\UserProviderInterface;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Security\GuardInterface;

/**
 * @codingStandardsIgnoreStart
 */
class SecurableMethodInvokerSpec extends ObjectBehavior
{
    public function let(
        MethodInvokerInterface $methodInvoker,
        UserProviderInterface $userProvider,
        GuardInterface $guard,
        Reader $annotationReader,
        PropertyAccessor $propertyAccessor
    ) {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpcExtensions\Security\Method\SecurableMethodInvoker');
        $this->beConstructedWith($methodInvoker, $userProvider, $guard, $annotationReader, $propertyAccessor);
        $this->shouldImplement(MethodInvokerInterface::class);
    }

    public function it_should_allow_method_invocation_if_guard_allows(
        MethodInvokerInterface $methodInvoker,
        UserProviderInterface $userProvider,
        GuardInterface $guard,
        Reader $annotationReader,
        PropertyAccessor $propertyAccessor
    ) {
        $securedService = new SecuredAreaApi();
        $requestObject = new \stdClass();
        $requestObject->prop1 = 'asd';
        $responseObject = new \stdClass();
        $responseObject->prop2 = 'asd';

        $attributeAnnotation = new Attribute();
        $attributeAnnotation->name = 'test';
        $attributeAnnotation->valueAt = 'test';

        $annotationReader
            ->getMethodAnnotation(new \ReflectionMethod($securedService, 'securedMethod'), Attribute::class)
            ->willReturn($attributeAnnotation)
            ->shouldBeCalled()
        ;

        $propertyAccessor->getValue($requestObject, 'test')
            ->willReturn('sec-attr-val')
            ->shouldBeCalled()
        ;

        $userProvider
            ->getUserId()
            ->willReturn(12)
            ->shouldBeCalled()
        ;

        $guard
            ->isGranted(12, 'test', 'sec-attr-val')
            ->willReturn(true)
            ->shouldBeCalled()
        ;

        $methodInvoker
            ->invoke([$securedService, 'securedMethod'], $requestObject)
            ->willReturn($responseObject)
            ->shouldBeCalled()
        ;

        $this
            ->invoke([$securedService, 'securedMethod'], $requestObject)
            ->shouldBe($responseObject)
        ;
    }

    public function it_should_filter_allowed_attributes(
        MethodInvokerInterface $methodInvoker,
        UserProviderInterface $userProvider,
        GuardInterface $guard,
        Reader $annotationReader,
        PropertyAccessor $propertyAccessor
    ) {
        $securedService = new SecuredAreaApi();
        $requestObject = new \stdClass();
        $requestObject->prop1 = 'asd';
        $responseObject = new \stdClass();
        $responseObject->prop2 = 'asd';

        $attributeAnnotation = new Attribute();
        $attributeAnnotation->name = 'test';
        $attributeAnnotation->valueAt = 'test';

        $annotationReader
            ->getMethodAnnotation(new \ReflectionMethod($securedService, 'securedMethod'), Attribute::class)
            ->willReturn($attributeAnnotation)
            ->shouldBeCalled()
        ;

        $propertyAccessor->getValue($requestObject, 'test')
            ->willReturn(['sec-attr-val'])
            ->shouldBeCalled()
        ;

        $userProvider
            ->getUserId()
            ->willReturn(12)
            ->shouldBeCalled()
        ;

        $guard
            ->filterGranted(12, 'test', ['sec-attr-val'])
            ->willReturn([])
            ->shouldBeCalled()
        ;

        $propertyAccessor
            ->setValue($requestObject, 'test', [])
            ->shouldBeCalled()
        ;

        $methodInvoker
            ->invoke([$securedService, 'securedMethod'], $requestObject)
            ->willReturn($responseObject)
            ->shouldBeCalled()
        ;

        $this
            ->invoke([$securedService, 'securedMethod'], $requestObject)
            ->shouldBe($responseObject)
        ;
    }

    public function it_should_not_invoke_method_if_guard_denies(
        MethodInvokerInterface $methodInvoker,
        UserProviderInterface $userProvider,
        GuardInterface $guard,
        Reader $annotationReader,
        PropertyAccessor $propertyAccessor
    ) {
        $securedService = new SecuredAreaApi();
        $requestObject = new \stdClass();
        $requestObject->prop1 = 'asd';
        $responseObject = new \stdClass();
        $responseObject->prop2 = 'asd';

        $attributeAnnotation = new Attribute();
        $attributeAnnotation->name = 'test';
        $attributeAnnotation->valueAt = 'test';

        $annotationReader
            ->getMethodAnnotation(new \ReflectionMethod($securedService, 'securedMethod'), Attribute::class)
            ->willReturn($attributeAnnotation)
            ->shouldBeCalled()
        ;

        $propertyAccessor->getValue($requestObject, 'test')
            ->willReturn('sec-attr-val')
            ->shouldBeCalled()
        ;

        $userProvider
            ->getUserId()
            ->willReturn(12)
            ->shouldBeCalled()
        ;

        $guard
            ->isGranted(12, 'test', 'sec-attr-val')
            ->willReturn(false)
            ->shouldBeCalled()
        ;

        $methodInvoker
            ->invoke([$securedService, 'securedMethod'], $requestObject)
            ->willReturn($responseObject)
            ->shouldNotBeCalled()
        ;

        $this
            ->shouldThrow(AccessDeniedException::class)
            ->duringInvoke([$securedService, 'securedMethod'], $requestObject)
        ;
    }
}

class SecuredAreaApi
{
    public function securedMethod(\stdClass $request)
    {
    }
}
