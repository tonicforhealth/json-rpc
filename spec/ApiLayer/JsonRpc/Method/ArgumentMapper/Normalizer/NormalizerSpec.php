<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer;

use PhpSpec\ObjectBehavior;

/**
 * @codingStandardsIgnoreStart
 */
class NormalizerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer\Normalizer');
    }

    public function it_should_map_complex_object_to_array()
    {
        $this
            ->normalize(new ComplexObject(new AnyObj(1, 1, 2), 'someVal'))
            ->shouldBeLike(['anyObj' => ['a' => 1, 'b' => 1, 'c' => 2], 'someProp' => 'someVal'])
        ;
    }

    public function it_should_map_simple_array_to_specified_simple_object()
    {
        $this
            ->denormalize(AnyObj::class, ['a' => 1, 'b' => 1, 'c' => 2])
            ->shouldBeLike(new AnyObj(1, 1, 2))
        ;
    }

    public function it_should_map_complex_array_to_complex_object()
    {
        $this
            ->denormalize(ComplexObject::class, ['anyObj' => ['a' => 1, 'b' => 1, 'c' => 2], 'someProp' => 'someVal'])
            ->shouldBeLike(new ComplexObject(new AnyObj(1, 1, 2), 'someVal'))
        ;
    }
}

class ComplexObject
{
    /** @var \spec\Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer\AnyObj */
    public $anyObj;

    public $someProp;

    public function __construct(AnyObj $anyObj, $someProp)
    {
        $this->anyObj = $anyObj;
        $this->someProp = $someProp;
    }
}

class AnyObj
{
    public $a, $b, $c;

    public function __construct($a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
}
