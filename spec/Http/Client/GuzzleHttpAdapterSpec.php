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
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use UglyGremlin\Layer\Exception\RequestException;

/**
 * Class GuzzleHttpAdapterSpec
 *
 * @package spec\UglyGremlin\Layer\Http\Client
 * @require GuzzleHttp\Client
 * @mixin \UglyGremlin\Layer\Http\Client\GuzzleHttpAdapter
 */
class GuzzleHttpAdapterSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Http\Client\GuzzleHttpAdapter');
        $this->shouldImplement('UglyGremlin\Layer\Http\ClientInterface');
    }

    function it_can_execute_a_request(Client $client, RequestInterface $request, ResponseInterface $response)
    {
        $request->getMethod()->willReturn('GET');
        $request->getUri()->willReturn('http://foo.bar');
        $request->getHeaders()->willReturn(['bar' => 'foo']);
        $request->getBody()->willReturn('payload');

        $client->request('GET', 'http://foo.bar', Argument::allOf(
            Argument::withEntry('headers', ['bar' => 'foo']),
            Argument::withEntry('json', 'payload')
        ))->willReturn($response);

        $this->execute($request)->shouldBe($response);
    }

    function it_will_handle_client_exceptions(
        Client $client,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $exception = new ClientException('foo', $request->getWrappedObject(), $response->getWrappedObject());

        $client->request(
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->willThrow($exception);

        $this->execute($request)->shouldBe($response);
    }

    function it_will_handle_server_exceptions(
        Client $client,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $exception = new ServerException('foo', $request->getWrappedObject(), $response->getWrappedObject());

        $client->request(
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->willThrow($exception);

        $this->execute($request)->shouldBe($response);
    }

    function it_wraps_other_exceptions(
        Client $client,
        RequestInterface $request
    ) {
        $exception        = new \Exception('foo');
        $wrappedException = new RequestException($request->getWrappedObject(), 'foo', 0, $exception);

        $client->request(
            Argument::any(),
            Argument::any(),
            Argument::any()
        )->willThrow($exception);

        $this->shouldThrow($wrappedException)->duringExecute($request);
    }
}
