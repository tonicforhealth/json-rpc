<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc;

use PhpSpec\ObjectBehavior;
use Tonic\Component\ApiLayer\JsonRpc\Method\Loader\LoaderInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvokerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Server;

/**
 * @codingStandardsIgnoreStart
 */
class ServerFactorySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\ServerFactory');
    }

    public function it_should_create_server_without_any_errors(
        LoaderInterface $loader,
        MethodInvokerInterface $methodInvoker
    ) {
        $this
            ->create($loader, $methodInvoker)
            ->shouldBeAnInstanceOf(Server::class)
        ;
    }
}
