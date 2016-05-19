<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseException
 *
 * @package UglyGremlin\Layer\Exception
 */
class ResponseException extends RequestException implements ExceptionInterface
{
    /**
     * API response
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * ResponseException constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param string            $message
     * @param int               $code
     * @param \Exception        $previous
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        $message = '',
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($request, $message, $code, $previous);
        $this->response = $response;
    }

    /**
     * Returns the API response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
