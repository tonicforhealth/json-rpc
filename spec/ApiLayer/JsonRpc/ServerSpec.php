<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc;

use PhpSpec\ObjectBehavior;
use Tonic\Component\ApiLayer\JsonRpc\Exception\Exception;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodDispatcherInterface;
use Tonic\Component\ApiLayer\JsonRpc\Request\Request;
use Tonic\Component\ApiLayer\JsonRpc\Request\RequestParserInterface;
use Tonic\Component\ApiLayer\JsonRpc\Response\Error;
use Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponse;
use Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponseFactory;
use Tonic\Component\ApiLayer\JsonRpc\Response\ResponseSerializerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Response\SuccessResponse;
use Tonic\Component\ApiLayer\JsonRpc\ServerInterface;

/**
 * @codingStandardsIgnoreStart
 */
class ServerSpec extends ObjectBehavior
{
    public function let(
        RequestParserInterface $requestParser,
        MethodDispatcherInterface $methodDispatcher,
        ResponseSerializerInterface $responseSerializer,
        ErrorResponseFactory $errorResponseFactory
    ) {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Server');
        $this->beConstructedWith($requestParser, $methodDispatcher, $responseSerializer, $errorResponseFactory);
        $this->shouldImplement(ServerInterface::class);
    }

    public function it_should_dispatch_method_with_specified_parameters(
        RequestParserInterface $requestParser,
        MethodDispatcherInterface $methodDispatcher,
        ResponseSerializerInterface $responseSerializer
    ) {
        $requestParser
            ->parse('{"jsonrpc":"2.0","method":"calculator.calculateProfit","params":{"month":"January"},"id":"calculate-january-profit"}')
            ->willReturn(new Request('2.0', 'calculate-january-profit', 'calculator.calculateProfit', ['month' => 'January']))
        ;

        $methodDispatcher
            ->dispatch('calculator.calculateProfit', ['month' => 'January'])
            ->willReturn(100000)
        ;

        $responseSerializer
            ->serializeResponse('2.0', new SuccessResponse('calculate-january-profit', 100000))
            ->willReturn('{"jsonrpc":"2.0","result":100000,"id":"calculate-january-profit"}')
            ->shouldBeCalled()
        ;

        $this
            ->handle('{"jsonrpc":"2.0","method":"calculator.calculateProfit","params":{"month":"January"},"id":"calculate-january-profit"}')
            ->shouldBeLike('{"jsonrpc":"2.0","result":100000,"id":"calculate-january-profit"}')
        ;
    }

    public function it_should_catch_any_exception_and_convert_it_to_error(
        RequestParserInterface $requestParser,
        MethodDispatcherInterface $methodDispatcher,
        ResponseSerializerInterface $responseSerializer,
        ErrorResponseFactory $errorResponseFactory
    ) {
        $requestParser
            ->parse('{"jsonrpc":"2.0","method":"calculator.calculateProfit","params":{"month":"January"},"id":"calculate-january-profit"}')
            ->willReturn(new Request('2.0', 'calculate-january-profit', 'calculator.calculateProfit', ['month' => 'January']))
        ;

        $methodDispatcher
            ->dispatch('calculator.calculateProfit', ['month' => 'January'])
            ->will(function () {
                throw new Exception('test', 32);
            })
        ;

        $errorResponseFactory
            ->createForException(new Exception('test', 32), 'calculate-january-profit')
            ->willReturn(ErrorResponse::applicationDefinedError('calculate-january-profit', 'test', 32))
        ;

        $responseSerializer
            ->serializeResponse('2.0', new ErrorResponse('calculate-january-profit', new Error('test', 32)))
            ->willReturn('{"jsonrpc":"2.0","error": {"code": 32, "message": "test"}')
            ->shouldBeCalled()
        ;

        $this
            ->handle('{"jsonrpc":"2.0","method":"calculator.calculateProfit","params":{"month":"January"},"id":"calculate-january-profit"}')
            ->shouldBeLike('{"jsonrpc":"2.0","error": {"code": 32, "message": "test"}')
        ;
    }
}
