<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation;

use Tonic\Component\ApiLayer\JsonRpc\Method\MethodCollection;
use Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation\MetadataExtractor;

class Generator
{
    /**
     * @var MetadataExtractor
     */
    private $metadataExtractor;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \Tonic\Component\ApiLayer\JsonRpcExtensions\Documentation\MetadataExtractor $metadataExtractor
     * @param \Twig_Environment $twig
     */
    public function __construct(MetadataExtractor $metadataExtractor, \Twig_Environment $twig)
    {
        $this->metadataExtractor = $metadataExtractor;
        $this->twig = $twig;
    }

    /**
     * @param MethodCollection $methodCollection
     * @param string $templateName
     *
     * @return string
     */
    public function generate(MethodCollection $methodCollection, $templateName = 'default.html.twig')
    {
        $metadata = $this->metadataExtractor->extract($methodCollection);

        return $this->twig->render($templateName, ['metadata' => $metadata]);
    }
}