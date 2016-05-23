<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use UglyGremlin\Layer\Api\RequestFactory;
use UglyGremlin\Layer\Api\ResponseChecker;
use UglyGremlin\Layer\Api\ResponseParser;
use UglyGremlin\Layer\Api\ResponseValidator;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Http\Exchange;
use UglyGremlin\Layer\Log\Logger;

/**
 * Class AbstractApiSpec
 *
 * @package spec\UglyGremlin\Layer\Api
 */
abstract class AbstractApiSpec extends ObjectBehavior
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var ResponseChecker
     */
    protected $checker;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var CollectionResponse
     */
    protected $collectionResponse;

    /**
     * @var ResponseValidator
     */
    protected $responseValidator;

    /**
     * @var ResponseParser
     */
    protected $responseParser;

    /**
     * @var Exchange
     */
    protected $exchange;

    function let(
        ClientInterface $httpClient,
        RequestFactory $requestFactory,
        ResponseValidator $responseValidator,
        ResponseParser $responseParser,
        Logger $logger,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->httpClient        = $httpClient;
        $this->requestFactory    = $requestFactory;
        $this->responseValidator = $responseValidator;
        $this->responseParser    = $responseParser;
        $this->logger            = $logger;
        $this->request           = $request;
        $this->response          = $response;
        $this->exchange          = new Exchange($request->getWrappedObject(), $response->getWrappedObject());

        $this->httpClient->execute($request)->willReturn($response);
        $this->responseValidator->validate($this->exchange)->willReturn($this->exchange);

        $this->beConstructedWith($httpClient, $requestFactory, $responseValidator, $responseParser, $logger);
    }

    protected function expectCollection()
    {
        $this->checker->parseCollection($this->request, $this->response)
            ->willReturn($this->collectionResponse);
    }
}
