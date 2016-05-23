<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Http\Client;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\Header\HeaderCollection;
use Guzzle\Http\Message\Request as GuzzleRequest;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Guzzle\Stream\StreamInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use UglyGremlin\Layer\Exception\RequestException;

/**
 * Class GuzzleHttpAdapterSpec
 *
 * @package spec\UglyGremlin\Layer\Http\Client
 * @require \Guzzle\Http\Client
 * @mixin \UglyGremlin\Layer\Http\Client\GuzzleAdapter
 */
class GuzzleAdapterSpec extends ObjectBehavior
{
    function let(Client $client, GuzzleResponse $guzzleResponse, StreamInterface $body, HeaderCollection $headers)
    {
        $guzzleResponse->getStatusCode()->willReturn(200);
        $guzzleResponse->getHeaders()->willReturn($headers);
        $guzzleResponse->getBody()->willReturn($body);
        $guzzleResponse->getBody(true)->willReturn('body');
        $body->getSize()->willReturn(100);
        $headers->toArray()->willReturn(['bar' => 'foo']);

        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Http\Client\GuzzleAdapter');
        $this->shouldImplement('UglyGremlin\Layer\Http\ClientInterface');
    }

    function it_can_execute_a_request(
        Client $client,
        RequestInterface $request,
        GuzzleRequest $guzzleRequest,
        GuzzleResponse $guzzleResponse
    ) {
        $request->getMethod()->willReturn('GET');
        $request->getUri()->willReturn('http://foo.bar');
        $request->getHeaders()->willReturn(['bar' => 'foo']);
        $request->getBody()->willReturn('payload');

        $client->createRequest('GET', 'http://foo.bar', ['bar' => 'foo'], 'payload')
            ->willReturn($guzzleRequest);

        $guzzleRequest->send()->willReturn($guzzleResponse);

        $this->execute($request)->shouldReturnAnInstanceOf('GuzzleHttp\Psr7\Response');
    }

    function it_will_handle_client_exceptions(
        Client $client,
        RequestInterface $request,
        GuzzleResponse $guzzleResponse,
        BadResponseException $exception
    ) {
        $exception->getResponse()->willReturn($guzzleResponse);

        $client->createRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->willThrow($exception->getWrappedObject());

        $this->execute($request)->shouldReturnAnInstanceOf('GuzzleHttp\Psr7\Response');
    }

    function it_will_wrap_the_exception_if_there_is_no_response(
        Client $client,
        RequestInterface $request,
        BadResponseException $exception
    ) {
        $exception->getResponse()->willReturn(null);
        $wrappedException = new RequestException($request->getWrappedObject(), "", 0, $exception->getWrappedObject());

        $client->createRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->willThrow($exception->getWrappedObject());

        $this->shouldThrow($wrappedException)->duringExecute($request);
    }

    function it_wraps_any_unknown_exceptions(
        Client $client,
        RequestInterface $request
    ) {
        $exception        = new \Exception('message');
        $wrappedException = new RequestException($request->getWrappedObject(), 'message', 0, $exception);

        $client->createRequest(Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->willThrow($exception);

        $this->shouldThrow($wrappedException)->duringExecute($request);
    }
}
