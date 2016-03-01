<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tonic\Component\ApiLayer\JsonRpc\Response\Error;

class ErrorResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponse');
    }

    function it_should_be_correctly_constructed()
    {
        $this->beConstructedWith('some-id', new Error('Some message', 204));

        $this->getId()->shouldBe('some-id');
        $this->getError()->shouldBeLike(new Error('Some message', 204));
    }

    function it_should_support_array_normalization()
    {
        $this->beConstructedWith('some-id', new Error('Some message', 204));

        $this->toArray()->shouldBeLike([
            'id' => 'some-id',
            'error' => [
                'code' => 204,
                'message' => 'Some message'
            ]
        ]);
    }
}
