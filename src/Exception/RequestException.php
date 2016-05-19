<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Exception;

use Psr\Http\Message\RequestInterface;

/**
 * Class RequestException
 *
 * Exception throw when some error is found executing the API request
 *
 * @package UglyGremlin\Layer\Exception
 */
class RequestException extends RuntimeException implements ExceptionInterface
{
    /**
     * API request
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * ResponseParseException constructor.
     *
     * @param RequestInterface $request
     * @param string           $message
     * @param int              $code
     * @param \Exception       $previous
     */
    public function __construct(RequestInterface $request, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
    }

    /**
     * Returns the API request
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
