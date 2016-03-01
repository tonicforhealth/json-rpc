<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Method;

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
     * @param $methodName
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
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->methods);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->methods);
    }

    /**
     * {@inheritdoc}
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
