<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\ArgumentMapperInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\Normalizer\NormalizerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidCallableArgumentsException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidMethodParametersException;

class ArgumentMapperSpec extends ObjectBehavior
{
    function let(NormalizerInterface $normalizer)
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Method\ArgumentMapper\ArgumentMapper');
        $this->beConstructedWith($normalizer);
        $this->shouldImplement(ArgumentMapperInterface::class);
    }

    function it_should_maps_arguments_to_object(NormalizerInterface $normalizer)
    {
        $normalizer
            ->denormalize(SomeRequestObj::class, ['a' => 1, 'b' => 2])
            ->willReturn(new SomeRequestObj(1, 2))
            ->shouldBeCalled()
        ;

        $this->mapToObject([new SomeGoodService(), 'someMethod'], ['a' => 1, 'b' => 2])->shouldBeLike(new SomeRequestObj(1, 2));
    }

    function it_should_throw_exception_for_indexed_arrays_of_arguments()
    {
        $this->shouldThrow(InvalidCallableArgumentsException::class)->duringMapToObject([new SomeGoodService(), 'someMethod'], [1, 2]);
    }

    function it_should_throw_exception_for_methods_with_more_than_one_parameter()
    {
        $this->shouldThrow(InvalidMethodParametersException::class)->duringMapToObject([new SomeGoodService(), 'invalidMethod'], []);
    }

    function it_should_throw_exception_for_method_with_parameter_without_type_hinting()
    {
        $this->shouldThrow(InvalidMethodParametersException::class)->duringMapToObject([new SomeGoodService(), 'invalidParamMethod'], []);
    }
}

class SomeRequestObj
{
    public $a, $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}

class SomeGoodService
{
    public function invalidParamMethod($a)
    {

    }

    public function invalidMethod(SomeRequestObj $someRequestObj1, SomeRequestObj $someRequestObj2)
    {
    }

    public function someMethod(SomeRequestObj $someRequestObj)
    {
    }
}

