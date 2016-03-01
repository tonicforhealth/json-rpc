<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Method\Loader;

use Doctrine\Common\Annotations\Reader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tonic\Component\ApiLayer\JsonRpc\Annotation\Method;
use Tonic\Component\ApiLayer\JsonRpc\Method\Loader\LoaderInterface;
use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;

class MutableAnnotationLoaderSpec extends ObjectBehavior
{
    function let(Reader $annotationReader)
    {
        $this->beAnInstanceOf('Tonic\Component\ApiLayer\JsonRpc\Method\Loader\MutableAnnotationLoader');
        $this->beConstructedWith($annotationReader);
        $this->shouldImplement(LoaderInterface::class);
    }

    function it_should_read_annotations_from_specified_instances(Reader $annotationReader)
    {
        $this->add(new AnnotatedService());

        $methodAnnotation = new Method();
        $methodAnnotation->name = 'some.method';
        $annotationReader
            ->getMethodAnnotation(new \ReflectionMethod(new AnnotatedService(), 'coolMethod'), Method::class)
            ->willReturn($methodAnnotation)
        ;

        $this->load()->shouldBeLike(new MethodCollection(['some.method' => [new AnnotatedService(), 'coolMethod']]));
    }
}

class AnnotatedService
{
    /**
     * @\Tonic\Component\ApiLayer\JsonRpc\Annotation\Method(name = "some.method")
     */
    public function coolMethod()
    {

    }
}