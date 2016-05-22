<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use UglyGremlin\Layer\Exception\ParseException;
use UglyGremlin\Layer\Http\Exchange;

/**
 * Class ResponseParser
 *
 * @package UglyGremlin\Layer\Api
 */
class ResponseParser
{
    const HEADER_COUNT = 'layer-count';

    /**
     * Parses the response expecting a collection
     *
     * @param Exchange $exchange
     *
     * @return \stdClass
     */
    public function parseObject(Exchange $exchange)
    {
        $object = $this->parseBody($exchange);

        if (!$object instanceof \stdClass) {
            throw new ParseException($exchange, "An object is expected in the API response for an entity");
        }

        return $object;
    }

    /**
     * Parses the response expecting a collection
     *
     * @param Exchange $exchange
     *
     * @return array
     */
    public function parseList(Exchange $exchange)
    {
        $response = $exchange->getResponse();
        $list = $this->parseBody($exchange);

        if (!is_array($list)) {
            throw new ParseException($exchange, "An array is expected in the API response for a collection");
        }

        $count = implode('', $response->getHeader(self::HEADER_COUNT));
        if (!is_numeric($count)) {
            throw new ParseException($exchange, "The total count is expected in the API response as a header");
        }

        return [$list, (int) $count];
    }

    /**
     * @param Exchange $exchange
     *
     * @return null|array|\stdClass
     */
    private function parseBody(Exchange $exchange)
    {
        $response = $exchange->getResponse();
        if ($response->getBody()->getSize() == 0) {
            return null;
        }

        $body = json_decode((string) $response->getBody());
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ParseException($exchange, "Response could not be parsed");
        }

        return $body;
    }
}
