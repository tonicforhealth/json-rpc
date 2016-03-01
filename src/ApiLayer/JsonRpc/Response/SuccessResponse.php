<?php

namespace Tonic\Component\ApiLayer\JsonRpc\Response;

/**
 * Represents success response.
 */
class SuccessResponse extends AbstractResponse
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * Constructor.
     *
     * @param mixed $id
     * @param mixed $result
     */
    public function __construct($id, $result)
    {
        parent::__construct($id);

        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'result' => $this->getResult(),
        ]);
    }
}
