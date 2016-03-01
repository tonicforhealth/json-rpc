<?php

namespace Tonic\Component\ApiLayer\JsonRpcExtensions\Security;

interface GuardInterface
{
    /**
     * Is user granted to access specified attribute value?
     *
     * @param string $userId
     * @param string $attributeName
     * @param string $attributeValue
     *
     * @return bool
     */
    public function isGranted($userId, $attributeName, $attributeValue);

    /**
     * Returns only allowed attributes.
     *
     * @param string $userId
     * @param string $attributeName
     * @param array $attributeValues
     *
     * @return array
     */
    public function filterGranted($userId, $attributeName, array $attributeValues);
}