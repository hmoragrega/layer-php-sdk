<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Http\Client;

use Guzzle\Http\Client as GuzzleClient;
use GuzzleHttp\Client as GuzzleHttpClient;
use UglyGremlin\Layer\Exception\RuntimeException;

/**
 * Class GuzzleAdapterFactory
 *
 * @package UglyGremlin\Layer\Http\Client
 */
class GuzzleAdapterFactory
{
    /**
     * Builds a guzzle adapter depending on the installed library and version
     * 
     * @param array $options
     *
     * @return GuzzleAdapter|GuzzleHttpAdapter|GuzzleHttpLegacyAdapter
     */
    public static function build(array $options = [])
    {
        if (class_exists('\Guzzle\Http\Client')) {
            return new GuzzleAdapter(new GuzzleClient('', $options));
        }
        
        if (class_exists('\GuzzleHttp\Client')) {
            return self::buildGuzzleHttpAdapter($options);
        }

        throw new RuntimeException("No suitable installation of a guzzle factory has been found");
    }

    /**
     * Builds a guzzle adapter suitable for the installed GuzzleHttp library 
     * 
     * @param array $options
     *
     * @return GuzzleHttpAdapter|GuzzleHttpLegacyAdapter
     */
    private static function buildGuzzleHttpAdapter(array $options)
    {
        $client = new GuzzleHttpClient($options);
        if (method_exists($client, 'createRequest') && method_exists($client, 'send')) {
            return new GuzzleHttpLegacyAdapter($client);
        }

        if (method_exists($client, 'request')) {
            return new GuzzleHttpAdapter($client);
        }

        throw new RuntimeException("The installed guzzle library is not compatible with the provided adapters");
    }
}
