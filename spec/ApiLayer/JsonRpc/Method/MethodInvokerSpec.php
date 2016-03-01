<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Method;

use PhpSpec\ObjectBehavior;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvokerInterface;

/**
 * @codingStandardsIgnoreStart
 */
class MethodInvokerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvoker');
        $this->shouldImplement(MethodInvokerInterface::class);
    }

    public function it_should_invokes_method(InvokableService $invokableService)
    {
        $invokableService
            ->invokableMethod(':arg')
            ->willReturn('some result:arg')
            ->shouldBeCalled()
        ;

        $this
            ->invoke([$invokableService->getWrappedObject(), 'invokableMethod'], ':arg')
            ->shouldReturn('some result:arg')
        ;
    }
}

class InvokableService
{
    public function invokableMethod($argument)
    {
        return 'some result'.$argument;
    }
}
