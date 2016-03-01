<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Tonic\Component\ApiLayer\JsonRpc\Response\Response;

class ResponseSerializerSpec extends ObjectBehavior
{
    function let()
    {
        $this->shouldHaveType('Tonic\Component\ApiLayer\JsonRpc\Response\ResponseSerializer');
    }

    function it_should_serializes_response_with_appropriate_version(Response $response)
    {
        $response->toArray()->willReturn(['result' => 'some result', 'someprop' => 'somevalue']);

        $this->serializeResponse('2.3', $response)->shouldBe('{"jsonrpc":"2.3","result":"some result","someprop":"somevalue"}');
    }
}
