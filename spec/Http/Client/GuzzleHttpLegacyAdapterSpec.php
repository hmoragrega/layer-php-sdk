<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Message\RequestInterface as GuzzleRequest;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponse;
use GuzzleHttp\Stream\StreamInterface;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use UglyGremlin\Layer\Exception\RequestException;

/**
 * Class GuzzleHttpAdapterSpec
 *
 * @package spec\UglyGremlin\Layer\Http\Client
 * @require \GuzzleHttp\Client
 * @require \GuzzleHttp\Message\ResponseInterface
 * @mixin \UglyGremlin\Layer\Http\Client\GuzzleHttpLegacyAdapter
 */
class GuzzleHttpLegacyAdapterSpec extends ObjectBehavior
{
    function let(Client $client, GuzzleResponse $guzzleResponse, StreamInterface $body)
    {
        if (!method_exists('GuzzleHttp\Client', 'createRequest')) {
            throw new SkippingException('The required dependency is not installed');
        }

        $guzzleResponse->getStatusCode()->willReturn(200);
        $guzzleResponse->getHeaders()->willReturn(['bar' => 'foo']);
        $guzzleResponse->getBody()->willReturn($body);
        $body->getSize()->willReturn(100);
        $body->getContents()->willReturn('body');

        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Http\Client\GuzzleHttpLegacyAdapter');
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

        $client->createRequest('GET', 'http://foo.bar', Argument::allOf(
            Argument::withEntry('headers', ['bar' => 'foo']),
            Argument::withEntry('json', 'payload')
        ))->willReturn($guzzleRequest);

        $client->send($guzzleRequest)->willReturn($guzzleResponse);

        $this->execute($request)->shouldReturnAnInstanceOf('GuzzleHttp\Psr7\Response');
    }

    function it_will_handle_client_exceptions(
        Client $client,
        RequestInterface $request,
        GuzzleRequest $guzzleRequest,
        GuzzleResponse $guzzleResponse
    ) {
        $exception = new ClientException('me', $guzzleRequest->getWrappedObject(), $guzzleResponse->getWrappedObject());

        $client->createRequest(Argument::any(), Argument::any(), Argument::any())->willThrow($exception);

        $this->execute($request)->shouldReturnAnInstanceOf('GuzzleHttp\Psr7\Response');
    }

    function it_will_wrap_the_exception_if_there_is_no_response(
        Client $client,
        RequestInterface $request,
        GuzzleRequest $guzzleRequest
    ) {
        $exception        = new ClientException('me', $guzzleRequest->getWrappedObject());
        $wrappedException = new RequestException($request->getWrappedObject(), 'me', 0, $exception);
        $client->createRequest(Argument::any(), Argument::any(), Argument::any())->willThrow($exception);

        $this->shouldThrow($wrappedException)->duringExecute($request);
    }

    function it_wraps_any_unknown_exceptions(
        Client $client,
        RequestInterface $request
    ) {
        $exception        = new \Exception('me');
        $wrappedException = new RequestException($request->getWrappedObject(), 'me', 0, $exception);
        $client->createRequest(Argument::any(), Argument::any(), Argument::any())->willThrow($exception);

        $this->shouldThrow($wrappedException)->duringExecute($request);
    }
}
