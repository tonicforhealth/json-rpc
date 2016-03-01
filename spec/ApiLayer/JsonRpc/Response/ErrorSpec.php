<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ErrorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Response\Error');
    }

    function it_should_be_correctly_constructed()
    {
        $this->beConstructedWith('Some error message', 205);

        $this->getCode()->shouldBe(205);
        $this->getMessage()->shouldBe('Some error message');
    }

    function it_should_support_array_normalization()
    {
        $this->beConstructedWith('Some error message', 205);

        $this->toArray()->shouldBeLike([
            'code' => 205,
            'message' => 'Some error message'
        ]);
    }
}
