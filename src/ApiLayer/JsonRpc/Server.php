<?php

namespace Tonic\Component\ApiLayer\JsonRpc;

use Tonic\Component\ApiLayer\JsonRpc\Method\MethodDispatcherInterface;
use Tonic\Component\ApiLayer\JsonRpc\Request\RequestParserInterface;
use Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponseFactory;
use Tonic\Component\ApiLayer\JsonRpc\Response\ResponseSerializerInterface;
use Tonic\Component\ApiLayer\JsonRpc\Response\SuccessResponse;

/**
 * Responsible for handling JSON-RPC request and provide appropriate JSON-RPC response.
 */
class Server implements ServerInterface
{
    /**
     * @var RequestParserInterface
     */
    private $requestParser;

    /**
     * @var MethodDispatcherInterface
     */
    private $methodDispatcher;

    /**
     * @var ResponseSerializerInterface
     */
    private $responseSerializer;
    /**
     * @var ErrorResponseFactory
     */
    private $errorResponseFactory;

    /**
     * Constructor.
     *
     * @param RequestParserInterface      $requestParser
     * @param MethodDispatcherInterface   $methodDispatcher
     * @param ResponseSerializerInterface $responseSerializer
     * @param ErrorResponseFactory        $errorResponseFactory
     */
    public function __construct(
        RequestParserInterface $requestParser,
        MethodDispatcherInterface $methodDispatcher,
        ResponseSerializerInterface $responseSerializer,
        ErrorResponseFactory $errorResponseFactory
    ) {
        $this->requestParser = $requestParser;
        $this->methodDispatcher = $methodDispatcher;
        $this->responseSerializer = $responseSerializer;
        $this->errorResponseFactory = $errorResponseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($requestContent)
    {
        $request = null;
        try {
            $request = $this->requestParser->parse($requestContent);
            $result = $this->methodDispatcher->dispatch($request->getMethod(), $request->getParams());
            $response = new SuccessResponse($request->getId(), $result);
            $responseContent = $this->responseSerializer->serializeResponse($request->getVersion(), $response);
        } catch (\Exception $e) {
            $response = $this->errorResponseFactory->createForException($e, $request ? $request->getId() : null);

            $responseContent = $this->responseSerializer->serializeResponse(
                $request ? $request->getVersion() : '2.0',
                $response
            );
        }

        return $responseContent;
    }
}
