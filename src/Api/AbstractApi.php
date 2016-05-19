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
use UglyGremlin\Layer\Exception\ResponseException;
use UglyGremlin\Layer\Http\ClientInterface;
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

    /**
     * The HTTP client to execute request.
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * The object that validates the API response
     *
     * @var ResponseChecker
     */
    private $checker;

    /**
     * @var UuidGeneratorInterface
     */
    private $uuidGenerator;

    /**
     * @var Config
     */
    private $config;

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
     * @param ResponseChecker        $checker
     * @param UuidGeneratorInterface $uuidGenerator
     * @param Config                 $config
     * @param Logger                 $logger
     */
    public function __construct(
        ClientInterface $httpClient,
        ResponseChecker $checker,
        UuidGeneratorInterface $uuidGenerator,
        Config $config,
        Logger $logger
    ) {
        $this->httpClient     = $httpClient;
        $this->checker        = $checker;
        $this->uuidGenerator  = $uuidGenerator;
        $this->config         = $config;
        $this->logger         = $logger;
    }

    /**
     * Returns the response checker
     *
     * @return ResponseChecker
     */
    protected function getChecker()
    {
        return $this->checker;
    }

    /**
     * Gets an entity response
     *
     * @param string $path
     *
     * @return \stdClass
     */
    protected function getEntity($path)
    {
        list($request, $response) = $this->get($path);

        return $this->checker->parseEntity($request, $response);
    }

    /**
     * Executes a GET method
     *
     * @param string $path
     * @param array  $params
     *
     * @return array
     */
    protected function get($path, array $params = [])
    {
        $request  = $this->buildRequest('GET', $this->getApiUrl($path, $params), $this->getHeaders());
        $response = $this->execute($request);

        return [$request, $response];
    }

    /**
     * Returns a response from the API for a POST method
     *
     * @param string                $path
     * @param array|stdClass|string $payload
     */
    protected function post($path, $payload)
    {
        $this->execute($this->buildRequest('POST', $this->getApiUrl($path), $this->getHeaders(), $payload));
    }

    /**
     * Returns a response from the API for a POST method
     *
     * @param string       $path
     * @param string|array $payload
     */
    protected function patch($path, $payload)
    {
        $this->execute($this->buildRequest('PATCH', $this->getApiUrl($path), $this->getPatchHeaders(), $payload));
    }

    /**
     * Returns a response from the API for a PUT method
     *
     * @param string                $path
     * @param array|stdClass|string $payload
     */
    protected function put($path, $payload)
    {
        $this->execute($this->buildRequest('PUT', $this->getApiUrl($path), $this->getHeaders(), $payload));
    }

    /**
     * Returns a response from the API for a GET method
     *
     * @param string $path
     */
    protected function delete($path)
    {
        $this->execute($this->buildRequest('DELETE', $this->getApiUrl($path), $this->getHeaders()));
    }

    /**
     * Gets the response
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    protected function execute(RequestInterface $request)
    {
        try {
            $response = $this->httpClient->execute($request);
            $this->checker->validate($request, $response);
            $this->logger->log($request, $response);

            return $response;
        } catch (ResponseException $exception) {
            $this->logger->log($request, $exception->getResponse());
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->log($request, isset($response) ? $response : null);
            throw new RequestException($request, $exception->getMessage(), 0, null, $exception);
        }
    }

    /**
     * Transform the payload to the expected string
     *
     * @param array|\stdClass|string $payload
     *
     * @return string
     */
    private function transformPayload($payload)
    {
        if (!is_string($payload)) {
            $payload = json_encode($payload);
        }

        return $payload;
    }

    /**
     * Builds a request
     *
     * @param string $method  HTTP method for the request
     * @param string $url     API endpoint URL
     * @param array  $headers Headers
     * @param mixed  $payload Message body
     *
     * @return Request
     */
    public function buildRequest($method, $url, array $headers = [], $payload = null)
    {
        return new Request($method, $url, $headers, $this->transformPayload($payload));
    }

    /**
     * Builds the final API URL
     *
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    private function getApiUrl($path, array $params = [])
    {
        $url = $this->config->getBaseUrl().'apps/'.$this->config->getAppId().'/'.$path;

        if (count($params) > 0) {
            $url .= '?'.http_build_query($params);
        }

        return $url;
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
