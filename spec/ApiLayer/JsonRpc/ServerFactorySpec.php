<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\ArgumentMapperInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\Loader\LoaderInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvokerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponseFactory;
use Tonic\Component\ApiLayer\JsonRpc\Server;

class ServerFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\ServerFactory');
    }

    function it_should_create_server_without_any_errors(
        LoaderInterface $loader,
        MethodInvokerInterface $methodInvoker
    )
    {
        $this
            ->create($loader, $methodInvoker)
            ->shouldBeAnInstanceOf(Server::class)
        ;
    }
}
