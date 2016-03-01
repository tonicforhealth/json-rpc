<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;
use Tonic\Component\Reflection\TypeResolver;

class MetadataExtractorSpec extends ObjectBehavior
{
    function let(TypeResolver $typeResolver)
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation\MetadataExtractor');
        $this->beConstructedWith($typeResolver);
    }

    function it_should_extract_metadata_from_method_collection(
        TypeResolver $typeResolver
    )
    {
        $typeResolver
            ->resolvePropertyType(new \ReflectionProperty(DocRequest::class, 'someElem'))
            ->willReturn(DocRequestElem::class)
        ;

        $typeResolver
            ->resolvePropertyType(new \ReflectionProperty(DocRequestElem::class, 'prop'))
            ->willReturn('string')
        ;

        $typeResolver
            ->resolvePropertyType(new \ReflectionProperty(DocRequestElem::class, 'req'))
            ->willReturn(DocRequest::class)
        ;

        $typeResolver
            ->resolvePropertyType(new \ReflectionProperty(DocResponse::class, 'docResponseElem'))
            ->willReturn(DocResponseElem::class)
        ;

        $typeResolver
            ->resolvePropertyType(new \ReflectionProperty(DocResponseElem::class, 'prop'))
            ->willReturn('string')
        ;

        $typeResolver
            ->resolveFunctionReturnType(new \ReflectionMethod(DocService::class, 'wellDocumentedFunction'))
            ->willReturn(DocResponse::class)
        ;

        $this
            ->extract(new MethodCollection([
                'doc.service' => [new DocService(), 'wellDocumentedFunction']
            ]))
            ->shouldBeLike([[
                'method' => 'doc.service',
                'description' => 'Some desc of method.',
                'parameters' => [
                    ['name' => 'someElem', 'description' => 'Some description of property.', 'type' => 'object', 'properties' => [
                        ['name' => 'prop', 'description' => '', 'type' => 'string'],
                        ['name' => 'req', 'description' => '', 'type' => 'object', 'properties' => []],
                    ]]
                ],
                'returns' => [[
                    'name' => 'docResponseElem',
                    'description' => 'Some desc of response.',
                    'type' => 'object',
                    'properties' => [[
                        'name' => 'prop',
                        'description' => '',
                        'type' => 'string'
                    ]]
                ]]
            ]])
        ;
    }
}

class DocRequest
{
    /**
     * Some description of property.
     *
     * @var DocRequestElem
     */
    public $someElem;
}

class DocRequestElem
{
    /**
     * @var string
     */
    public $prop;

    /**
     * @var DocRequest
     */
    public $req;
}

class DocResponse
{
    /**
     * Some desc of response.
     *
     * @var DocResponseElem
     */
    public $docResponseElem;
}

class DocResponseElem
{
    /**
     * @var string
     */
    public $prop;
}

class DocService
{
    /**
     * Some desc of method.
     *
     * @param DocRequest $docRequest
     *
     * @return DocResponse
     */
    public function wellDocumentedFunction(DocRequest $docRequest)
    {
    }
}
