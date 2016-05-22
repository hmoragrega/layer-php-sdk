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
use UglyGremlin\Layer\Http\Exchange;

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
     * @param Exchange    $exchange
     * @param string      $message
     * @param int         $code
     * @param \Exception  $previous
     */
    public function __construct(
        Exchange $exchange,
        $message = '',
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($exchange->getRequest(), $message, $code, $previous);
        $this->response = $exchange->getResponse();
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
