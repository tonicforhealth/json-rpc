<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

class ResponseSerializer implements ResponseSerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function serializeResponse($version, Response $response)
    {
        return json_encode(array_merge(['jsonrpc' => $version], $response->toArray()));
    }
}