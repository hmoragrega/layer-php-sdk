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
use UglyGremlin\Layer\Exception\ResponseException;
use UglyGremlin\Layer\Http\ClientInterface;
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
     * The HTTP client to execute request.
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * The factory to build API request.
     *
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * The object that validates the API response
     *
     * @var ResponseChecker
     */
    private $checker;

    /**
     * It logs the requests and the responses.
     *
     * @var Logger
     */
    private $logger;

    /**
     * Client constructor.
     *
     * @param ClientInterface $httpClient
     * @param RequestFactory  $requestFactory
     * @param ResponseChecker $checker
     * @param Logger          $logger
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactory $requestFactory,
        ResponseChecker $checker,
        Logger $logger
    ) {
        $this->httpClient     = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->checker        = $checker;
        $this->logger         = $logger;
    }

    /**
     * Returns the request factory
     *
     * @return RequestFactory
     */
    protected function getRequestFactory()
    {
        return $this->requestFactory;
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
     * @param $path
     * @param array $params
     *
     * @return array
     */
    protected function get($path, array $params = [])
    {
        $request  = $this->getRequestFactory()->get($path, $params);
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
        $this->execute($this->requestFactory->post($path, $this->transformPayload($payload)));
    }

    /**
     * Returns a response from the API for a POST method
     *
     * @param string       $path
     * @param string|array $payload
     */
    protected function patch($path, $payload)
    {
        $this->execute($this->requestFactory->patch($path, $this->transformPayload($payload)));
    }

    /**
     * Returns a response from the API for a PUT method
     *
     * @param string                $path
     * @param array|stdClass|string $payload
     */
    protected function put($path, $payload)
    {
        $this->execute($this->requestFactory->put($path, $this->transformPayload($payload)));
    }

    /**
     * Returns a response from the API for a GET method
     *
     * @param string $path
     */
    protected function delete($path)
    {
        $this->execute($this->requestFactory->delete($path));
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
}
