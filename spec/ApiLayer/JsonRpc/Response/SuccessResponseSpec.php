<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SuccessResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Response\SuccessResponse');
    }

    function it_should_be_correctly_constructed()
    {
        $this->beConstructedWith('some-id', ['some mixed result', 1]);

        $this->getId()->shouldBe('some-id');
        $this->getResult()->shouldBeLike(['some mixed result', 1]);
    }

    function it_should_support_array_normalization()
    {
        $this->beConstructedWith('some-id', ['some mixed result', 1]);

        $this->toArray()->shouldBeLike([
            'id' => 'some-id',
            'result' => ['some mixed result', 1]
        ]);
    }
}
