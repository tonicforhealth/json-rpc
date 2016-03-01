<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Method;

use PhpSpec\ObjectBehavior;

/**
 * @codingStandardsIgnoreStart
 */
class MethodCollectionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->shouldHaveType('Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection');
    }

    public function it_should_add_callable_to_collection_with_alias()
    {
        $this->has('sampleService.sampleMethod')->shouldBe(false);

        $this->add('sampleService.sampleMethod', function () {});

        $this->get('sampleService.sampleMethod')->shouldBeAnInstanceOf(\Closure::class);
    }
}
