<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;

/**
 * @codingStandardsIgnoreStart
 */
class ErrorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Response\Error');
    }

    public function it_should_be_correctly_constructed()
    {
        $this->beConstructedWith('Some error message', 205);

        $this->getCode()->shouldBe(205);
        $this->getMessage()->shouldBe('Some error message');
    }

    public function it_should_support_array_normalization()
    {
        $this->beConstructedWith('Some error message', 205);

        $this->toArray()->shouldBeLike([
            'code' => 205,
            'message' => 'Some error message',
        ]);
    }
}
