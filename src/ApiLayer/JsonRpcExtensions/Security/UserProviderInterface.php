<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Security;

/**
 * Provides user identifier from anywhere.
 */
interface UserProviderInterface
{
    /**
     * @return string
     */
    public function getUserId();
}
