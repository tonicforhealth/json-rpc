<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

/**
 * Represents JSON-RPC error in response.
 */
class Error
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $code;

    /**
     * Constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage()
        ];
    }
}