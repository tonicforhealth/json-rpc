<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

interface ResponseSerializerInterface
{
    /**
     * Serializes response corresponding to appropriate version.
     *
     * @param string   $version
     * @param Response $response
     *
     * @return string
     */
    public function serializeResponse($version, Response $response);
}
