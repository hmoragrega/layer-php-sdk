<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Http;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ExchangeSpec
 *
 * @package UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Http\Exchange
 */
class ExchangeSpec extends ObjectBehavior
{
    function let(RequestInterface $request, ResponseInterface $response)
    {
        $this->beConstructedWith($request, $response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Http\Exchange');
    }

    function it_can_return_the_request(RequestInterface $request)
    {
        $this->getRequest()->shouldReturn($request);
    }

    function it_can_return_the_response(ResponseInterface $response)
    {
        $this->getResponse()->shouldReturn($response);
    }
}
