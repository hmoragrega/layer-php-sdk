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
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class RequestFactory
 *
 * @package UglyGremlin\Layer\Api
 */
class RequestFactory
{
    const GET    = 'GET';
    const POST   = 'POST';
    const PUT    = 'PUT';
    const PATCH  = 'PATCH';
    const DELETE = 'DELETE';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var UuidGeneratorInterface
     */
    private $uuidGenerator;

    /**
     * RequestFactory constructor.
     *
     * @param Config                 $config
     * @param UuidGeneratorInterface $uuidGenerator
     */
    public function __construct(Config $config, UuidGeneratorInterface $uuidGenerator)
    {
        $this->config = $config;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * Builds a request
     *
     * @param string                          $method
     * @param string                          $path
     * @param string|resource|StreamInterface $payload
     *
     * @return RequestInterface
     */
    public function create($method, $path, $payload = null)
    {
        $url = $this->getApiUrl($path);

        $headers = $method === RequestFactory::PATCH
            ? $this->getPatchHeaders()
            : $this->getHeaders();

        return new Request($method, $url, $headers, $this->encode($payload));
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
}
