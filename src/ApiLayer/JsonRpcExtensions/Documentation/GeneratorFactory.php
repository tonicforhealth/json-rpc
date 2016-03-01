<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation;

use Doctrine\Common\Annotations\PhpParser;
use Tonic\Component\Reflection\TypeResolver;

class GeneratorFactory
{
    /**
     * @return Generator
     */
    public function createDefault()
    {
        return new Generator(
            new MetadataExtractor(new TypeResolver(new PhpParser())),
            new \Twig_Environment(new \Twig_Loader_Filesystem([
                __DIR__.'/Resources',
            ]))
        );
    }
}
