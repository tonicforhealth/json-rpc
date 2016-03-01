<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Method;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MethodCollectionSpec extends ObjectBehavior
{
    function let()
    {
        $this->shouldHaveType('Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection');
    }

    function it_should_add_callable_to_collection_with_alias()
    {
        $this->has('sampleService.sampleMethod')->shouldBe(false);

        $this->add('sampleService.sampleMethod', function () {});

        $this->get('sampleService.sampleMethod')->shouldBeAnInstanceOf(\Closure::class);
    }
}
