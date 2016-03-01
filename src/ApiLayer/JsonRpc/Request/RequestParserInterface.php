<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Request;

/**
 * Responsible for request parsing.
 */
interface RequestParserInterface
{
    /**
     * Parses request and creates request object.
     *
     * @param string $content Raw JSON-RPC request
     *
     * @return Request
     */
    public function parse($content);
}