<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Request;

/**
 * Represents JSON-RPC request.
 */
class Request
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var mixed
     */
    private $id;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $params;

    /**
     * Constructor.
     *
     * @param string $version
     * @param mixed  $id
     * @param string $method
     * @param array  $params
     */
    public function __construct($version, $id, $method, array $params = [])
    {
        $this->version = $version;
        $this->id = $id;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
