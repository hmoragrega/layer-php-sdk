<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use UglyGremlin\Layer\Exception\RequestException;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Http\Exchange;
use UglyGremlin\Layer\Http\RequestFactory;
use UglyGremlin\Layer\Log\Logger;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class AbstractApi
 *
 * It contains the minimal code to execute requests against the platform REST API
 *
 * @package UglyGremlin\Layer\Api
 */
abstract class AbstractApi
{
    const API_BASE_URL = 'https://api.layer.com/';

    const STATUS_OK         = 200;
    const STATUS_CREATED    = 201;
    const STATUS_NO_CONTENT = 204;

    const HEADER_COUNT = 'layer-count';

    /**
     * @var Config
     */
    private $config;

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
     * @var UuidGeneratorInterface
     */
    private $uuidGenerator;

    /**
     * It logs the requests and the responses.
     *
     * @var Logger
     */
    private $logger;

    /**
     * Client constructor.
     *
     * @param Config                 $config
     * @param ClientInterface        $httpClient
     * @param RequestFactory         $requestFactory
     * @param ResponseValidator      $responseValidator
     * @param ResponseParser         $responseParser
     * @param UuidGeneratorInterface $uuidGenerator
     * @param Logger                 $logger
     */
    public function __construct(
        Config $config,
        ClientInterface $httpClient,
        RequestFactory $requestFactory,
        ResponseValidator $responseValidator,
        ResponseParser $responseParser,
        UuidGeneratorInterface $uuidGenerator,
        Logger $logger
    ) {
        $this->config            = $config;
        $this->httpClient        = $httpClient;
        $this->requestFactory    = $requestFactory;
        $this->uuidGenerator     = $uuidGenerator;
        $this->logger            = $logger;
        $this->responseValidator = $responseValidator;
        $this->responseParser    = $responseParser;
    }

    public function getEntity($path)
    {
        return $this->responseParser->parseObject($this->execute(RequestFactory::GET, $path));
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
     * @param string $url
     * @param null   $payload
     *
     * @return Exchange
     */
    private function execute($method, $url, $payload = null)
    {
        $request  = $this->buildRequest($method, $url, $payload);
        $response = $this->executeRequest($request);
        
        $exchange = new Exchange($request, $response);

        return $this->responseValidator->validate($exchange);
    }

    /**
     * Builds a request
     * 
     * @param      $method
     * @param      $path
     * @param null $payload
     *
     * @return RequestInterface
     */
    private function buildRequest($method, $path, $payload = null)
    {
        $headers = $method === RequestFactory::PATCH
            ? $this->getPatchHeaders()
            : $this->getHeaders();

        return $this->requestFactory->create($method, $this->getApiUrl($path), $headers, $this->encode($payload));
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

    /**
     * Encode the payload to the expected string
     *
     * @param array|\stdClass|string $payload
     *
     * @return string
     */
    private function encode($payload)
    {
        if ($payload !== null && !is_string($payload)) {
            $payload = json_encode($payload);
        }

        return $payload;
    }

    /**
     * Builds the final API URL
     *
     * @param string $path
     *
     * @return string
     */
    private function getApiUrl($path)
    {
        return $this->config->getBaseUrl().'apps/'.$this->config->getAppId().'/'.$path;
    }

    /**
     * Returns the headers to send on the requests
     *
     * @return array
     */
    private function getHeaders()
    {
        return [
            'Accept'        => 'application/vnd.layer+json; version=1.1',
            'Authorization' => 'Bearer '.$this->config->getAppToken(),
            'Content-Type'  => 'application/json',
            'User-Agent'    => 'UglyGremlin\'s Layer PHP SDK. 1.0.0',
            'If-None-Match' => $this->uuidGenerator->getUniqueId(),
        ];
    }

    /**
     * Returns the headers to send on the PATCH requests
     *
     * @return array
     */
    private function getPatchHeaders()
    {
        $headers = $this->getHeaders();
        $headers['Content-Type']           = 'application/vnd.layer-patch+json';
        $headers['X-HTTP-Method-Override'] = 'PATCH';

        return $headers;
    }
}
