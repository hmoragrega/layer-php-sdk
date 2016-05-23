<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use UglyGremlin\Layer\Exception\RequestException;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Http\Exchange;
use UglyGremlin\Layer\Log\Logger;

/**
 * Class AbstractApi
 *
 * It contains the minimal code to execute requests against the platform REST API
 *
 * @package UglyGremlin\Layer\Api
 */
abstract class AbstractApi
{
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ResponseValidator
     */
    private $responseValidator;

    /**
     * @var ResponseParser
     */
    private $responseParser;

    /**
     * The HTTP client to execute request.
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * It logs the requests and the responses.
     *
     * @var Logger
     */
    private $logger;

    /**
     * Client constructor.
     *
     * @param ClientInterface        $httpClient
     * @param RequestFactory         $requestFactory
     * @param ResponseValidator      $responseValidator
     * @param ResponseParser         $responseParser
     * @param Logger                 $logger
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactory $requestFactory,
        ResponseValidator $responseValidator,
        ResponseParser $responseParser,
        Logger $logger
    ) {
        $this->httpClient        = $httpClient;
        $this->requestFactory    = $requestFactory;
        $this->logger            = $logger;
        $this->responseValidator = $responseValidator;
        $this->responseParser    = $responseParser;
    }

    /**
     * @param $path
     *
     * @return array
     */
    public function getEntity($path)
    {
        return (array) $this->responseParser->parseObject($this->execute(RequestFactory::GET, $path));
    }

    /**
     * @param string $path
     * @param array  $params
     *
     * @return array
     */
    public function getCollection($path, array $params = [])
    {
        if (count($params) > 0) {
            $path .= '?'.http_build_query($params);
        }

        /** @var ResponseInterface $response */
        return $this->responseParser->parseList($this->execute(RequestFactory::GET, $path));
    }
    public function post($path, $payload = null)
    {
        $this->execute(RequestFactory::POST, $path, $payload);
    }
    public function put($path, $payload = null)
    {
        $this->execute(RequestFactory::PUT, $path, $payload);
    }
    public function patch($path, $payload = null)
    {
        $this->execute(RequestFactory::PATCH, $path, $payload);
    }
    public function delete($path)
    {
        $this->execute(RequestFactory::DELETE, $path);
    }

    /**
     * @param string $method
     * @param string $path
     * @param null   $payload
     *
     * @return Exchange
     */
    private function execute($method, $path, $payload = null)
    {
        $request  = $this->requestFactory->create($method, $path, $payload);
        $response = $this->executeRequest($request);

        $exchange = new Exchange($request, $response);

        return $this->responseValidator->validate($exchange);
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    private function executeRequest(RequestInterface $request)
    {
        try {
            $response = $this->httpClient->execute($request);
            $this->logger->log($request, $response);

            return $response;

        } catch (\Exception $exception) {
            $this->logger->log($request);
            throw new RequestException($request, $exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
