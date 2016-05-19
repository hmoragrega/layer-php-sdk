<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use UglyGremlin\Layer\Exception\RequestException;

/**
 * Interface ClientInterface
 *
 * The classes that implement this interface are capable of executing HTTP requests
 *
 * @package UglyGremlin\Layer\Http
 */
interface ClientInterface
{
    /**
     * Executes a HTTP request
     *
     * @param RequestInterface $request The request to execute
     *
     * @return ResponseInterface
     *
     * @throws RequestException When there is an error executing the request
     */
    public function execute(RequestInterface $request);
}
