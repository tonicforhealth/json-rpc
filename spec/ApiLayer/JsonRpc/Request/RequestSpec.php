<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Request;

use PhpSpec\ObjectBehavior;

/**
 * @codingStandardsIgnoreStart
 */
class RequestSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Request\Request');
    }

    public function it_should_be_correctly_constructed()
    {
        $this->beConstructedWith('2.12', 'sample-id', 'sampleService.sampleMethod', ['param1' => 1]);

        $this->getVersion()->shouldBe('2.12');
        $this->getId()->shouldBe('sample-id');
        $this->getMethod()->shouldBe('sampleService.sampleMethod');
        $this->getParams()->shouldBeLike(['param1' => 1]);
    }
}
