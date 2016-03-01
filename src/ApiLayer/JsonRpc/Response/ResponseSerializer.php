<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

/**
 * Responds for response serialization to JSON.
 */
class ResponseSerializer implements ResponseSerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function serializeResponse($version, AbstractResponse $response)
    {
        return json_encode(array_merge(['jsonrpc' => $version], $response->toArray()));
    }
}
