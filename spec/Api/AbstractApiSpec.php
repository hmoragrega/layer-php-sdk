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
use UglyGremlin\Layer\Api\CollectionResponse;
use UglyGremlin\Layer\Api\RequestFactory;
use UglyGremlin\Layer\Api\ResponseChecker;
use UglyGremlin\Layer\Http\ClientInterface;
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

    function let(
        ClientInterface $httpClient,
        RequestFactory $requestFactory,
        ResponseChecker $checker,
        Logger $logger,
        RequestInterface $request,
        ResponseInterface $response,
        CollectionResponse $collectionResponse
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->checker = $checker;
        $this->logger = $logger;
        $this->request = $request;
        $this->response = $response;
        $this->collectionResponse = $collectionResponse;

        $this->httpClient->execute($request)->willReturn($response);
        $this->httpClient->execute($request)->willReturn($response);
        $this->checker->validate($request, $response)->willReturn($response);

        $this->beConstructedWith($httpClient, $requestFactory, $checker, $logger);
    }

    protected function expectCollection()
    {
        $this->checker->parseCollection($this->request, $this->response)
            ->willReturn($this->collectionResponse);
    }

    protected function expectEntity()
    {
        $this->checker->parseEntity($this->request, $this->response)
            ->willReturn($this->collectionResponse);
    }
}
