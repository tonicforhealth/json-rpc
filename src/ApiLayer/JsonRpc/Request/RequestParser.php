<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Request;

use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\InvalidRequestException;
use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\ParseException;

class RequestParser implements RequestParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($content)
    {
        $data = json_decode($content, true);
        if (0 !== json_last_error()) {
            throw new ParseException(sprintf('Invalid JSON syntax: "%s"', json_last_error_msg()));
        }

        if (! is_array($data)) {
            throw new InvalidRequestException('Request is not valid JSON');
        }

        $this->guard($data);

        return new Request($data['jsonrpc'], $data['id'], $data['method'], $data['params']);
    }

    /**
     * @param array $data
     *
     * @throws InvalidRequestException
     */
    private function guard(array $data)
    {
        $difference = array_diff(['jsonrpc', 'method', 'id', 'params'], array_keys($data));

        if (0 < count($difference)) {
            throw new InvalidRequestException(sprintf('Request attributes are missed: %s', join(', ', $difference)));
        }

        if (! is_array($data['params'])) {
            throw new InvalidRequestException('Parameters should have an object structure');
        }
    }
}