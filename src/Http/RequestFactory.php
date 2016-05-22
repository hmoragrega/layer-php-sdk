<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Http;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

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
     * Builds a request
     *
     * @param string                          $method
     * @param string                          $url
     * @param array                           $headers
     * @param string|resource|StreamInterface $payload
     *
     * @return RequestInterface
     */
    public function create($method, $url, array $headers = [], $payload = null)
    {
        return new Request($method, $url, $headers, $payload);
    }
}
