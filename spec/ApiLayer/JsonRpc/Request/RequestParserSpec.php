<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Request;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\InvalidRequestException;
use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\ParseException;
use Tonic\Component\ApiLayer\JsonRpc\Request\Request;
use Tonic\Component\ApiLayer\JsonRpc\Request\RequestParserInterface;

class RequestParserSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Request\RequestParser');
        $this->shouldImplement(RequestParserInterface::class);
    }

    function it_should_parse_json_and_create_request_object()
    {
        $this
            ->parse('{"jsonrpc":"2.0", "id": "abc.1", "method": "someMethod", "params": [1, 2, 3]}')
            ->shouldBeLike(new Request("2.0", "abc.1", "someMethod", [1, 2, 3]))
        ;
    }

    function it_should_throw_exception_for_invalid_json()
    {
        $this
            ->shouldThrow(ParseException::class)
            ->duringParse('{"jsonrpc":"2.: [1, 2, 3]}')
        ;
    }

    function it_should_throw_exception_for_invalid_request_without_protocol_version()
    {
        $this
            ->shouldThrow(InvalidRequestException::class)
            ->duringParse('{"id": "abc.1", "method": "someMethod", "params": [1, 2, 3]}')
        ;
    }

    function it_should_throw_exception_for_invalid_request_without_method()
    {
        $this
            ->shouldThrow(InvalidRequestException::class)
            ->duringParse('{"id": "abc.1", "params": [1, 2, 3]}')
        ;
    }

    function it_should_throw_exception_for_invalid_request_without_params()
    {
        $this
            ->shouldThrow(InvalidRequestException::class)
            ->duringParse('{"jsonrpc":"2.0", "id": "abc.1", "method": "someMethod"}')
        ;
    }

    function it_should_throw_exception_for_invalid_request_without_id()
    {
        $this
            ->shouldThrow(InvalidRequestException::class)
            ->duringParse('{"jsonrpc":"2.0", "method": "someMethod", "params": []}')
        ;
    }

    function it_should_throw_exception_for_invalid_request_with_invalid_params()
    {
        $this
            ->shouldThrow(InvalidRequestException::class)
            ->duringParse('{"jsonrpc":"2.0", "id": "abc.1", "method": "someMethod", "params": 1}')
        ;
    }
}
