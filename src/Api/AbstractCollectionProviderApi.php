<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

/**
 * Class AbstractCollectionProviderApi
 *
 * @package UglyGremlin\Layer\Api
 */
abstract class AbstractCollectionProviderApi extends AbstractApi
{
    /**
     * Queries for a collection to the API
     *
     * @param string      $path
     * @param int|null    $limit
     * @param string|null $fromId
     * @param array       $params
     *
     * @return array
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
}
