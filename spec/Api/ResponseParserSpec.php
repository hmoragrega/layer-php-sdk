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
use UglyGremlin\Layer\Exception\ParseException;
use UglyGremlin\Layer\Http\Exchange;

/**
 * Class ResponseParserSpec
 *
 * @package UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\ResponseParser
 */
class ResponseParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\ResponseParser');
    }

    function it_can_parse_an_object(Exchange $exchange)
    {
        $response = new Response(200, [], '{}');
        $exchange->getResponse()->willReturn($response);

        $this->parseObject($exchange)->shouldBeAnInstanceOf('\stdClass');
    }

    function it_can_parse_a_list(Exchange $exchange)
    {
        $response = new Response(200, ['layer-count' => 100], '[]');
        $exchange->getResponse()->willReturn($response);

        $expected = [[], 100];

        $this->parseList($exchange)->shouldReturn($expected);
    }

    function it_throws_an_exception_if_the_decode_fails(Exchange $exchange)
    {
        $response = new Response(200, [], 'invalid');
        $request  = new Request('GET', 'foo'); 
        $exchange->getResponse()->willReturn($response);
        $exchange->getRequest()->willReturn($request);

        $exception = new ParseException($request, $response, "The response is not a valid json");

        $this->shouldThrow($exception)->duringParseObject($exchange);
    }

    function it_throws_an_exception_if_the_entity_does_not_contain_an_object(Exchange $exchange)
    {
        $response = new Response(200, [], '[]');
        $request  = new Request('GET', 'foo');
        $exchange->getResponse()->willReturn($response);
        $exchange->getRequest()->willReturn($request);

        $exception = new ParseException($request, $response, "An object is expected in the API response for an entity");

        $this->shouldThrow($exception)->duringParseObject($exchange);
    }

    function it_throws_an_exception_if_the_lists_does_not_contain_an_array(Exchange $exchange)
    {
        $response = new Response(200, [], '{}');
        $request  = new Request('GET', 'foo'); 
        $exchange->getResponse()->willReturn($response);
        $exchange->getRequest()->willReturn($request);

        $exception = new ParseException($request, $response, "The response should contain an array");

        $this->shouldThrow($exception)->duringParseList($exchange);
    }

    function it_throws_an_exception_if_the_lists_does_not_contain_the_total(Exchange $exchange)
    {
        $response = new Response(200, [], '[]');
        $request  = new Request('GET', 'foo'); 
        $exchange->getResponse()->willReturn($response);
        $exchange->getRequest()->willReturn($request);

        $exception = new ParseException($request, $response, "The response should contain the total count");

        $this->shouldThrow($exception)->duringParseList($exchange);
    }
}
