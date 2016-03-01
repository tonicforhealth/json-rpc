<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Security;

interface UserProviderInterface
{
    /**
     * @return string
     */
    public function getUserId();
}
