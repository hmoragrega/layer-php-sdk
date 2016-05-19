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
use UglyGremlin\Layer\Exception\ParseException;
use UglyGremlin\Layer\Exception\ResponseException;

/**
 * Class ResponseChecker
 *
 * It can create exceptions based on the response status code
 *
 * @package UglyGremlin\Layer\Api
 */
class ResponseChecker
{
    const HEADER_COUNT = 'layer-count';

    const STATUS_OK           = 200;
    const STATUS_CREATED      = 201;
    const STATUS_NO_CONTENT   = 204;
    const STATUS_BAD_REQUEST  = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND    = 404;
    const STATUS_CONFLICT     = 409;
    const STATUS_GONE         = 410;

    /**
     * A map to get the correct exception from the status code
     *
     * @var array
     */
    private static $errors = [
        self::STATUS_BAD_REQUEST  => '\UglyGremlin\Layer\Exception\BadRequestException',
        self::STATUS_UNAUTHORIZED => '\UglyGremlin\Layer\Exception\BadRequestException',
        self::STATUS_NOT_FOUND    => '\UglyGremlin\Layer\Exception\NotFoundException',
        self::STATUS_CONFLICT     => '\UglyGremlin\Layer\Exception\ConflictException',
        self::STATUS_GONE         => '\UglyGremlin\Layer\Exception\GoneException',
    ];

    /**
     * Validates if the request was successful
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function validate(RequestInterface $request, ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();

        if (in_array($statusCode, [self::STATUS_OK, self::STATUS_CREATED, self::STATUS_NO_CONTENT])) {
            return $response;
        }

        if (isset(self::$exceptions[$statusCode])) {
            $error     = $this->parseEntity($request, $response);
            $exception = self::$errors[$statusCode];
            throw new $exception($request, $response, $error->message, $error->code);
        }

        throw new ResponseException($request, $response, "API failure");
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return \stdClass
     */
    public function parseEntity(RequestInterface $request, ResponseInterface $response)
    {
        $json = $this->parseBodyResponse($request, $response);

        if (!$json instanceof \stdClass) {
            throw new ParseException($request, $response, "A stdClass object is expected in the API response");
        }

        return $json;
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return CollectionResponse
     */
    public function parseCollection(RequestInterface $request, ResponseInterface $response)
    {
        $list = $this->parseBodyResponse($request, $response);

        if (!is_array($list)) {
            throw new ParseException($request, $response, "An array is expected in the API response");
        }

        $count = $response->getHeader(self::HEADER_COUNT);
        if (!array_key_exists(0, $count) || !is_numeric($count[0])) {
            throw new ParseException($request, $response, "The total count is expected in the API response");
        }

        return new CollectionResponse($list, (int) $count[0]);
    }

    /**
     * Parses the response body
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    private function parseBodyResponse(RequestInterface $request, ResponseInterface $response)
    {
        if ((int) $response->getBody()->getSize() == 0) {
            throw new ParseException($request, $response, "The response body is empty");
        }

        $json = json_decode((string) $response->getBody());
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ParseException($request, $response, "The response could not be parsed: ", json_last_error_msg());
        }

        return $json;
    }
}
