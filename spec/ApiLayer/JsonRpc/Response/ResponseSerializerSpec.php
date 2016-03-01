<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;
use Tonic\Component\ApiLayer\JsonRpc\Response\AbstractResponse;

/**
 * @codingStandardsIgnoreStart
 */
class ResponseSerializerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->shouldHaveType('Tonic\Component\ApiLayer\JsonRpc\Response\ResponseSerializer');
    }

    public function it_should_serializes_response_with_appropriate_version(AbstractResponse $response)
    {
        $response->toArray()->willReturn(['result' => 'some result', 'someprop' => 'somevalue']);

        $this->serializeResponse('2.3', $response)->shouldBe('{"jsonrpc":"2.3","result":"some result","someprop":"somevalue"}');
    }
}
