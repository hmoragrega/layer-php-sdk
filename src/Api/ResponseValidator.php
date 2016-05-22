<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use UglyGremlin\Layer\Exception\BadRequestException;
use UglyGremlin\Layer\Exception\ConflictException;
use UglyGremlin\Layer\Exception\NotFoundException;
use UglyGremlin\Layer\Exception\ResponseException;
use UglyGremlin\Layer\Exception\UnauthorizedException;
use UglyGremlin\Layer\Http\Exchange;
use UglyGremlin\Layer\Model\Error;

/**
 * Class ResponseChecker
 *
 * It can create exceptions based on the response status code
 *
 * @package UglyGremlin\Layer\Api
 */
class ResponseValidator
{
    const STATUS_OK           = 200;
    const STATUS_CREATED      = 201;
    const STATUS_NO_CONTENT   = 204;
    const STATUS_BAD_REQUEST  = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND    = 404;
    const STATUS_CONFLICT     = 409;
    const STATUS_GONE         = 410;
    
    private $exceptionMap = [
        self::STATUS_NOT_FOUND    => 'UglyGremlin\Layer\Exception\NotFoundException',
        self::STATUS_GONE         => 'UglyGremlin\Layer\Exception\GoneException',
        self::STATUS_CONFLICT     => 'UglyGremlin\Layer\Exception\ConflictException',
        self::STATUS_BAD_REQUEST  => 'UglyGremlin\Layer\Exception\BadRequestException',
        self::STATUS_UNAUTHORIZED => 'UglyGremlin\Layer\Exception\UnauthorizedException',
    ];

    /**
     * @var ResponseParser
     */
    private $parser;

    /**
     * ResponseValidator constructor.
     *
     * @param ResponseParser $parser
     */
    public function __construct(ResponseParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Validates if the request was successful
     *
     * @param Exchange $exchange
     *
     * @return Exchange
     */
    public function validate(Exchange $exchange)
    {
        $statusCode = $exchange->getResponse()->getStatusCode();

        if (!in_array($statusCode, [self::STATUS_OK, self::STATUS_CREATED, self::STATUS_NO_CONTENT])) {
            $this->throwResponseException($exchange, $statusCode);
        }

        return $exchange;
    }

    /**
     * Throws the correct response exception based on the status code
     *
     * @param Exchange $exchange
     * @param int      $statusCode
     */
    private function throwResponseException(Exchange $exchange, $statusCode)
    {
        $error = new Error((array) $this->parser->parseObject($exchange));

        if (isset($this->exceptionMap[$statusCode])) {
            $exception = new $this->exceptionMap[$statusCode];
            throw new $exception($exchange, $error->message, $error->code);
        }

        throw new ResponseException($exchange, $error->message, $error->code);
    }
}
