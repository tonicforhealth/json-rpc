<?php

namespace Tonic\Component\Reflection;

use Doctrine\Common\Annotations\PhpParser;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use phpDocumentor\Reflection\DocBlock\Tag\VarTag;

/**
 * Resolves types by comments.
 */
class TypeResolver
{
    /**
     * @var PhpParser
     */
    private $phpParser;

    /**
     * @param PhpParser $phpParser
     */
    public function __construct(PhpParser $phpParser)
    {
        $this->phpParser = $phpParser;
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return null|string
     */
    public function resolvePropertyType(\ReflectionProperty $reflectionProperty)
    {
        $docBlock = new DocBlock($reflectionProperty);
        $varTags = $docBlock->getTagsByName('var');
        if (count($varTags) == 0) {
            return null;
        }

        /** @var VarTag $varTag */
        $varTag = reset($varTags);
        $type = $varTag->getType();

        $isCollection = false;
        if ($extractedType = $this->extractFromCollectionType($type)) {
            $isCollection = true;
            $type = $extractedType;
        }

        if (static::isTypeObject($type)) {
            $type = $this->resolveClassName($type, $reflectionProperty->getDeclaringClass());
        }

        return $type.($isCollection ? '[]' : '');
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isTypeObject($type)
    {
        switch (strtolower($type)) {
            case 'int':
            case 'integer':
            case 'string':
            case 'array':
            case 'float':
            case 'double':
            case 'real':
            case 'bool':
            case 'boolean':
            case 'resource':
            case 'mixed':
            case 'null':
            case 'long':
            case 'numeric':
            case 'callable':
                return false;
            default:
                return true;
        }
    }

    private function resolveClassName($type, \ReflectionClass $usingClass)
    {
        if (strpos($type, '\\') === 0 && class_exists($type)) {
            return $type;
        }

        if (strpos($type, '\\') === 0) {
            $type = substr($type, 1);
        }

        $aliases = $this->phpParser->parseClass($usingClass);
        $alias = strtolower($type);

        if (!isset($aliases[$alias])) {
            return $usingClass->getNamespaceName().'\\'.$type;
        }

        return $aliases[$alias];
    }

    /**
     * @param \ReflectionMethod $reflectionFunction
     *
     * @return string
     */
    public function resolveFunctionReturnType(\ReflectionMethod $reflectionFunction)
    {
        $docBlock = new DocBlock($reflectionFunction);
        $returnTags = $docBlock->getTagsByName('return');
        /** @var ReturnTag $returnTag */
        $returnTag = reset($returnTags);

        $type = $returnTag->getType();

        $isCollection = false;
        if ($extractedType = $this->extractFromCollectionType($type)) {
            $isCollection = true;
            $type = $extractedType;
        }

        if (static::isTypeObject($type)) {
            $type = $this->resolveClassName($type, $reflectionFunction->getDeclaringClass());
        }

        return $type.($isCollection ? '[]' : '');
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    private function extractFromCollectionType($type)
    {
        if (($pos = strpos($type, '[')) !== false) {
            $type = substr($type, 0, $pos);

            return $type;
        }

        return null;
    }
}
