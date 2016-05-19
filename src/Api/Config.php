<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

/**
 * Class CollectionResponse
 *
 * It represents a layer API response for a collection
 *
 * @package UglyGremlin\Layer\Api
 */
class Config
{
    const API_BASE_URL = 'https://api.layer.com/';

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $appToken;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * Config constructor.
     *
     * @param string $appId
     * @param string $appToken
     * @param string $baseUrl
     */
    public function __construct($appId, $appToken, $baseUrl = self::API_BASE_URL)
    {
        $this->appId = $appId;
        $this->appToken = $appToken;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getAppToken()
    {
        return $this->appToken;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
