<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Method;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\ArgumentMapperInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\MethodNotFoundException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Loader\LoaderInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodDispatcherInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvokerInterface;

class MethodDispatcherSpec extends ObjectBehavior
{
    function let(
        LoaderInterface $loader,
        ArgumentMapperInterface $argumentMapper,
        MethodInvokerInterface $methodInvoker
    )
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Method\MethodDispatcher');
        $this->beConstructedWith($loader, $argumentMapper, $methodInvoker);
        $this->shouldImplement(MethodDispatcherInterface::class);
    }

    function it_should_invoke_specified_method(
        LoaderInterface $loader,
        ArgumentMapperInterface $argumentMapper,
        MethodInvokerInterface $methodInvoker,
        SampleService $sampleService
    )
    {
        $loader
            ->load()
            ->willReturn(new MethodCollection(['sampleMethod' => [$sampleService->getWrappedObject(), 'sampleMethod']]))
        ;

        $argumentMapper
            ->mapToObject([$sampleService, 'sampleMethod'], [1, 2, 3])
            ->willReturn([1, 2, 3])
            ->shouldBeCalled()
        ;

        $methodInvoker
            ->invoke([$sampleService->getWrappedObject(), 'sampleMethod'], [1, 2, 3])
            ->willReturn(6)
            ->shouldBeCalled()
        ;

        $this->dispatch('sampleMethod', [1, 2, 3])->shouldBe(6);
    }

    function it_should_throw_exception_if_method_not_found(
        LoaderInterface $loader
    ) {
        $loader->load()->willReturn(new MethodCollection());

        $this
            ->shouldThrow(MethodNotFoundException::class)
            ->duringDispatch('sampleMethod', [1, 2, 3])
        ;
    }
}

class SampleService
{
    public function sampleMethod($a, $b, $c)
    {
        return $a + $b + $c;
    }
}
