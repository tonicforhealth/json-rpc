<?php

namespace Tonic\Component\ApiLayer\JsonRpc;

use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\ArgumentMapper;
use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer\Normalizer;
use Tonic\Component\ApiLayer\JsonRpc\Method\Loader\LoaderInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodDispatcher;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvoker;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodInvokerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Request\RequestParser;
use Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponseFactory;
use Tonic\Component\ApiLayer\JsonRpc\Response\ResponseSerializer;

class ServerFactory
{
    /**
     * @param LoaderInterface $loader
     * @param MethodInvokerInterface|null $methodInvoker
     * @param bool $exposeInternalExceptions
     *
     * @return Server
     */
    public function create(
        LoaderInterface $loader,
        MethodInvokerInterface $methodInvoker = null,
        $exposeInternalExceptions = false
    )
    {
        if (null === $methodInvoker) {
            $methodInvoker = new MethodInvoker();
        }

        return new Server(
            new RequestParser(),
            new MethodDispatcher($loader, new ArgumentMapper(new Normalizer()), $methodInvoker),
            new ResponseSerializer(),
            new ErrorResponseFactory($exposeInternalExceptions)
        );
    }
}