<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

/**
 * Represents JSON-RPC response object.
 */
abstract class AbstractResponse
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * Constructor.
     *
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
        ];
    }
}
