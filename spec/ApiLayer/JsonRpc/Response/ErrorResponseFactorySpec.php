<?php

namespace spec\Tonic\Component\ApiLayer\JsonRpc\Response;

use PhpSpec\ObjectBehavior;
use Tonic\Component\ApiLayer\JsonRpc\Exception\Exception;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidCallableArgumentsException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\InvalidMethodParametersException;
use Tonic\Component\ApiLayer\JsonRpc\Method\Exception\MethodNotFoundException;
use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\InvalidRequestException;
use Tonic\Component\ApiLayer\JsonRpc\Request\Exception\ParseException;
use Tonic\Component\ApiLayer\JsonRpc\Response\Error;
use Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponse;

/**
 * @codingStandardsIgnoreStart
 */
class ErrorResponseFactorySpec extends ObjectBehavior
{
    public function let()
    {
        $this->shouldHaveType('Tonic\Component\ApiLayer\JsonRpc\Response\ErrorResponseFactory');
    }

    public function it_should_return_appropriate_response_for_parse_exception()
    {
        $this
            ->createForException(new ParseException())
            ->shouldBeLike(ErrorResponse::parseError());
    }

    public function it_should_return_appropriate_response_for_invalid_request_exception()
    {
        $this
            ->createForException(new InvalidRequestException())
            ->shouldBeLike(ErrorResponse::invalidRequestError());
    }

    public function it_should_return_appropriate_response_for_invalid_params_for_indexed_array()
    {
        $this
            ->createForException(new InvalidCallableArgumentsException(), 'a')
            ->shouldBeLike(ErrorResponse::invalidParamsError('a'));
    }

    public function it_should_return_method_not_found_error()
    {
        $this
            ->createForException(new MethodNotFoundException(), 'a')
            ->shouldBeLike(ErrorResponse::methodNotFoundError('a'));
    }

    public function it_should_return_appropriate_response_for_invalid_params_for_any_other_problem()
    {
        $this
            ->createForException(new InvalidMethodParametersException(), 'a')
            ->shouldBeLike(ErrorResponse::invalidParamsError('a'));
    }

    public function it_should_return_application_defined_error()
    {
        $this
            ->createForException(new Exception('some message', 42), 'a')
            ->shouldBeLike(ErrorResponse::applicationDefinedError('a', 'some message', 42));
    }

    public function it_should_return_application_error_for_any_other_error()
    {
        $this
            ->createForException(new \RuntimeException('some message', 12), 42)
            ->shouldBeLike(ErrorResponse::applicationError(42));
    }

    public function it_should_have_ability_to_expose_internal_exceptions()
    {
        $this->exposeInternalExceptions();

        $this
            ->createForException(new \RuntimeException('some message', 12), 42)
            ->shouldBeLike(new ErrorResponse(42, new Error('some message', 12)))
        ;
    }
}
