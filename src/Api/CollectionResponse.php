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
class CollectionResponse
{
    /**
     * List of objects in a collection response
     *
     * @var \stdClass[]
     */
    private $list;

    /**
     * @var int
     */
    private $total;

    /**
     * CollectionResponse constructor.
     *
     * @param \stdClass[] $list
     * @param int         $total
     */
    public function __construct(array $list, $total)
    {
        $this->list  = $list;
        $this->total = $total;
    }

    /**
     * Returns the list of objects in a collection response
     *
     * @return \stdClass[]
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }
}
