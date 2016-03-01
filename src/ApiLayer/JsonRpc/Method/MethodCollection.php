<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

/**
 * Holds collection of methods for JSON-RPC server.
 */
class MethodCollection implements \Iterator
{
    /**
     * @var array
     */
    private $methods = [];

    /**
     * Constructor.
     *
     * @param array $methods
     */
    public function __construct(array $methods = [])
    {
        foreach ($methods as $methodName => $callable) {
            $this->add($methodName, $callable);
        }
    }

    /**
     * @param string   $methodName
     * @param callable $callable
     *
     * @return MethodCollection
     */
    public function add($methodName, callable $callable)
    {
        $this->methods[$methodName] = $callable;

        return $this;
    }

    /**
     * @param string $methodName
     *
     * @return bool
     */
    public function has($methodName)
    {
        return array_key_exists($methodName, $this->methods);
    }

    /**
     * @param string $methodName
     *
     * @return callable|null
     */
    public function get($methodName)
    {
        if (!$this->has($methodName)) {
            return null;
        }

        return $this->methods[$methodName];
    }

    /**
     * @return callable
     */
    public function current()
    {
        return current($this->methods);
    }

    /**
     * @return callable
     */
    public function next()
    {
        return next($this->methods);
    }

    /**
     * @return string
     */
    public function key()
    {
        return key($this->methods);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $key = key($this->methods);

        return ($key !== false && $key !== null);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        reset($this->methods);
    }
}
