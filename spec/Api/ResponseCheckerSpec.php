<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use UglyGremlin\Layer\Exception\ResponseException;

/**
 * Class ResponseCheckerSpec
 *
 * @package UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\ResponseChecker
 */
class ResponseCheckerSpec extends ObjectBehavior
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var StreamInterface
     */
    private $body;

    function let(RequestInterface $request, ResponseInterface $response, StreamInterface $body)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->body     = $body;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\ResponseChecker');
    }

    /**
     * @dataProvider successfulStatusCodes
     */
    function it_can_validate_the_response_from_http_status_code($statusCode)
    {
        $this->response->getStatusCode()->willReturn($statusCode);
        
        $this->validate($this->request, $this->response)->shouldReturn($this->response);
    }

    /**
     * @dataProvider mappedStatusCodes
     */
    function it_throws_a_custom_api_exception_if_possible($statusCode, $exception)
    {
        $error = new \stdClass();
        $error->message = 'foo';
        $error->code    = 123;

        $this->response->getStatusCode()->willReturn($statusCode);
        $this->response->getBody()->willReturn($this->body);
        $this->body->getSize()->willReturn(100);
        $this->body->__toString()->willReturn(json_encode($error));

        $exception = new $exception(
            $this->request->getWrappedObject(),
            $this->response->getWrappedObject(),
            $error->message,
            $error->code
        );

        $this->shouldThrow($exception)->duringValidate($this->request, $this->response);
    }

    function it_throws_a_general_exception_for_other_status_codes()
    {
        $this->response->getStatusCode()->willReturn(500);

        $exception = new ResponseException(
            $this->request->getWrappedObject(),
            $this->response->getWrappedObject(),
            "API failure"
        );

        $this->shouldThrow($exception)->duringValidate($this->request, $this->response);
    }

    function it_can_parse_a_collection_from_a_response()
    {
        $this->parseCollection($this->request, $this->response);
    }

    function successfulStatusCodes()
    {
        return [[200], [201], [204]];
    }

    function mappedStatusCodes()
    {
        return [
            [400, '\UglyGremlin\Layer\Exception\BadRequestException'],
            [401, '\UglyGremlin\Layer\Exception\BadRequestException'],
            [404, '\UglyGremlin\Layer\Exception\NotFoundException'],
            [409, '\UglyGremlin\Layer\Exception\ConflictException'],
            [410, '\UglyGremlin\Layer\Exception\GoneException'],
        ];
    }
}
