<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;

/**
 * @codingStandardsIgnoreStart
 */
class SuccessResponseSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Response\SuccessResponse');
    }

    public function it_should_be_correctly_constructed()
    {
        $this->beConstructedWith('some-id', ['some mixed result', 1]);

        $this->getId()->shouldBe('some-id');
        $this->getResult()->shouldBeLike(['some mixed result', 1]);
    }

    public function it_should_support_array_normalization()
    {
        $this->beConstructedWith('some-id', ['some mixed result', 1]);

        $this->toArray()->shouldBeLike([
            'id' => 'some-id',
            'result' => ['some mixed result', 1],
        ]);
    }
}
