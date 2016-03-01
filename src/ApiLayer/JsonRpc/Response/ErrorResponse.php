<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

class ErrorResponse extends Response
{
    /**
     * Parse error.
     */
    const CODE_PARSE_ERROR = -32700;

    /**
     * Invalid request.
     */
    const CODE_INVALID_REQUEST_ERROR = -32600;

    /**
     * Method not found.
     */
    const CODE_METHOD_NOT_FOUND_ERROR = -32601;

    /**
     * Invalid params.
     */
    const CODE_INVALID_PARAMS_ERROR = -32602;

    /**
     * Internal application error.
     */
    const CODE_APPLICATION_ERROR = -32603;

    /**
     * @var Error
     */
    private $error;

    /**
     * @param string $message
     *
     * @return ErrorResponse
     */
    public static function parseError($message = 'Parse error')
    {
        return new static(null, new Error($message, self::CODE_PARSE_ERROR));
    }

    /**
     * @param string $message
     *
     * @return ErrorResponse
     */
    public static function invalidRequestError($message = 'Invalid request')
    {
        return new static(null, new Error($message, self::CODE_INVALID_REQUEST_ERROR));
    }

    /**
     * @param string $id
     * @param string $message
     *
     * @return ErrorResponse
     */
    public static function invalidParamsError($id, $message = 'Invalid params')
    {
        return new static($id, new Error($message, self::CODE_INVALID_PARAMS_ERROR));
    }

    /**
     * @param $id
     * @param string $message
     *
     * @return ErrorResponse
     */
    public static function methodNotFoundError($id, $message = 'Method not found')
    {
        return new static($id, new Error($message, self::CODE_METHOD_NOT_FOUND_ERROR));
    }

    /**
     * @param string $id
     * @param string $message
     *
     * @return ErrorResponse
     */
    public static function applicationError($id, $message = 'Internal error')
    {
        return new static($id, new Error($message, self::CODE_APPLICATION_ERROR));
    }

    /**
     * @param string $id
     * @param string $message
     * @param int    $code
     *
     * @return ErrorResponse
     */
    public static function applicationDefinedError($id, $message, $code)
    {
        return new static($id, new Error($message, $code));
    }

    /**
     * Constructor.
     *
     * @param mixed $id
     * @param Error $error
     */
    public function __construct($id, Error $error)
    {
        parent::__construct($id);

        $this->error = $error;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'error' => $this->getError()->toArray(),
        ]);
    }
}
