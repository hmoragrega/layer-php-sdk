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
use UglyGremlin\Layer\Api\Config;
use UglyGremlin\Layer\Api\ResponseChecker;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Http\RequestFactory;
use UglyGremlin\Layer\Log\Logger;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

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
        UuidGeneratorInterface $uuidGenerator,
        Logger $logger,
        RequestInterface $request,
        ResponseInterface $response,
        CollectionResponse $collectionResponse
    ) {
        $config = new Config('appId', 'appToken');
        $uuidGenerator->getUniqueId()->willReturn('uuid');

        $this->httpClient     = $httpClient;
        $this->checker        = $checker;
        $this->requestFactory = $requestFactory;
        $this->logger         = $logger;
        $this->request        = $request;
        $this->response       = $response;
        $this->collectionResponse = $collectionResponse;
        $this->collectionResponse->getList()->willReturn([1, 2, 3]);
        $this->collectionResponse->getTotal()->willReturn(100);

        $this->httpClient->execute($request)->willReturn($response);
        $this->httpClient->execute($request)->willReturn($response);
        $this->checker->validate($request, $response)->willReturn($response);

        $this->beConstructedWith($httpClient, $checker, $uuidGenerator, $requestFactory, $config, $logger);
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

    protected function getHeaders()
    {
        return [
            'Accept'        => 'application/vnd.layer+json; version=1.1',
            'Authorization' => 'Bearer appToken',
            'Content-Type'  => 'application/json',
            'User-Agent'    => 'UglyGremlin\'s Layer PHP SDK. 1.0.0',
            'If-None-Match' => 'uuid',
        ];
    }

    protected function getPatchHeaders()
    {
        return [
            'Accept'                 => 'application/vnd.layer+json; version=1.1',
            'Authorization'          => 'Bearer appToken',
            'Content-Type'           => 'application/vnd.layer-patch+json',
            'User-Agent'             => 'UglyGremlin\'s Layer PHP SDK. 1.0.0',
            'If-None-Match'          => 'uuid',
            'X-HTTP-Method-Override' => 'PATCH',
        ];
    }
}
