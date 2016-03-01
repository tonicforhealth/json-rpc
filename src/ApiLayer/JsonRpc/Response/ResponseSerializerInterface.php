<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

/**
 * Responds for response serialization to JSON.
 */
interface ResponseSerializerInterface
{
    /**
     * Serializes response corresponding to appropriate version.
     *
     * @param string           $version
     * @param AbstractResponse $response
     *
     * @return string
     */
    public function serializeResponse($version, AbstractResponse $response);
}
