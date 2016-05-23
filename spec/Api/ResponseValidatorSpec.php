<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use UglyGremlin\Layer\Api\ResponseParser;
use UglyGremlin\Layer\Http\Exchange;

/**
 * Class ResponseCheckerSpec
 *
 * @package UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\ResponseValidator
 */
class ResponseValidatorSpec extends ObjectBehavior
{
    /** @var Exchange */
    private $exchange;

    /** @var ResponseParser */
    private $responseParser;

    function let(Exchange $exchange, ResponseParser $responseParser)
    {
        $this->exchange       = $exchange;
        $this->responseParser = $responseParser;

        $this->beConstructedWith($responseParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\ResponseValidator');
    }

    /**
     * @dataProvider successfulStatusCodes
     */
    function it_can_validate_the_response_from_http_status_code($statusCode)
    {
        $response = new Response($statusCode);
        $this->exchange->getResponse()->willReturn($response);
        
        $this->validate($this->exchange)->shouldReturn($this->exchange);
    }

    /**
     * @dataProvider mappedStatusCodes
     */
    function it_throws_a_custom_api_exception($statusCode, $exception)
    {
        $error = new \stdClass();
        $error->message = 'foo';
        $error->code    = 123;

        $request  = new Request('FOO', 'var');
        $response = new Response($statusCode);
        $this->exchange->getResponse()->willReturn($response);
        $this->exchange->getRequest()->willReturn($request);

        $this->responseParser->parseObject($this->exchange)->willReturn($error);

        $exception = new $exception(
            $request,
            $response,
            $error->message,
            $error->code
        );

        $this->shouldThrow($exception)->duringValidate($this->exchange);
    }

    function successfulStatusCodes()
    {
        return [[200], [201], [204]];
    }

    function mappedStatusCodes()
    {
        return [
            [400, '\UglyGremlin\Layer\Exception\BadRequestException'],
            [401, '\UglyGremlin\Layer\Exception\UnauthorizedException'],
            [404, '\UglyGremlin\Layer\Exception\NotFoundException'],
            [409, '\UglyGremlin\Layer\Exception\ConflictException'],
            [410, '\UglyGremlin\Layer\Exception\GoneException'],
            [500, '\UglyGremlin\Layer\Exception\ResponseException'],
        ];
    }
}
