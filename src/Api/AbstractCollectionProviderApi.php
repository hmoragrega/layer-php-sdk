<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use UglyGremlin\Layer\Http\Response;

/**
 * Class CollectionProviderApi
 *
 * @package UglyGremlin\Layer\Api
 */
abstract class AbstractCollectionProviderApi extends AbstractApi
{
    const COUNT_HEADER = 'layer-count';

    /**
     * Queries for a collection to the API
     *
     * @param string      $path
     * @param int|null    $limit
     * @param string|null $fromId
     * @param array       $params
     *
     * @return CollectionResponse
     */
    protected function query($path, $limit = null, $fromId = null, array $params = [])
    {
        if ($limit !== null) {
            $params['page_size'] = $limit;
        }

        if ($fromId !== null) {
            $params['fromId'] = $fromId;
        }

        return $this->getCollection($path, $params);
    }

    /**
     * Gets an collection response
     *
     * @param string $path
     * @param array  $params
     *
     * @return CollectionResponse
     */
    protected function getCollection($path, array $params = [])
    {
        list($request, $response) = $this->get($path, $params);

        return $this->getChecker()->parseCollection($request, $response);
    }
}
