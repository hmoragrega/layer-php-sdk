<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use GuzzleHttp\Psr7\Request;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class RequestFactory
 *
 * @package UglyGremlin\Layer\Api
 */
class RequestFactory
{
    const API_BASE_URL = 'https://api.layer.com/';

    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_PATCH  = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var UuidGeneratorInterface
     */
    private $uuidGenerator;

    /**
     * Application identifier
     *
     * @var string
     */
    private $appId;

    /**
     * Application token
     *
     * @var string
     */
    private $appToken;

    /**
     * API base url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * RequestFactory constructor.
     *
     * @param UuidGeneratorInterface $uuidGenerator
     * @param string                 $appId
     * @param string                 $appToken
     * @param string                 $baseUrl
     */
    public function __construct(UuidGeneratorInterface $uuidGenerator, $appId, $appToken, $baseUrl = self::API_BASE_URL)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->appId         = $appId;
        $this->appToken      = $appToken;
        $this->baseUrl       = $baseUrl;
    }

    /**
     * Builds a GET request
     *
     * @param string $path
     * @param array  $params
     *
     * @return Request
     */
    public function get($path, array $params = [])
    {
        return new Request(self::METHOD_GET, $this->getApiUrl($path, $params), $this->getHeaders());
    }

    /**
     * Builds a POST request
     *
     * @param string      $path
     * @param string|null $payload
     *
     * @return Request
     */
    public function post($path, $payload)
    {
        return new Request(self::METHOD_POST, $this->getApiUrl($path), $this->getHeaders(), $payload);
    }

    /**
     * Builds a PUT request
     *
     * @param string      $path
     * @param string|null $payload
     *
     * @return Request
     */
    public function put($path, $payload)
    {
        return new Request(self::METHOD_PUT, $this->getApiUrl($path), $this->getHeaders(), $payload);
    }

    /**
     * Builds a PATCH request
     *
     * @param string      $path
     * @param string|null $payload
     *
     * @return Request
     */
    public function patch($path, $payload)
    {
        return new Request(self::METHOD_PATCH, $this->getApiUrl($path), $this->getPatchHeaders(), $payload);
    }

    /**
     * Builds a DELETE request
     *
     * @param string $path
     *
     * @return Request
     */
    public function delete($path)
    {
        return new Request(self::METHOD_DELETE, $this->getApiUrl($path), $this->getHeaders());
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
        $url = $this->baseUrl.'apps/'.$this->appId.'/'.$path;

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
            'Authorization' => 'Bearer '.$this->appToken,
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
