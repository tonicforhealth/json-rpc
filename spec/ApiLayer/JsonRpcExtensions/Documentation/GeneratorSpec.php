<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation\MetadataExtractor;

class GeneratorSpec extends ObjectBehavior
{
    function let(
        MetadataExtractor $metadataExtractor,
        \Twig_Environment $twig
    )
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation\Generator');
        $this->beConstructedWith($metadataExtractor, $twig);
    }

    function it_should_generate_readable_documentation(
        MetadataExtractor $metadataExtractor,
        \Twig_Environment $twig
    )
    {
        $methodCollection = new MethodCollection();

        $metadataExtractor
            ->extract($methodCollection)
            ->willReturn(['some_data' => 'about_methods'])
        ;

        $twig
            ->render('path_to_template.html.twig', ['metadata' => ['some_data' => 'about_methods']])
            ->willReturn('some_data about_methods')
        ;

        $this
            ->generate($methodCollection, 'path_to_template.html.twig')
            ->shouldBeLike('some_data about_methods')
        ;
    }
}
