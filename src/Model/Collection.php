<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class Collection
 *
 * @package UglyGremlin\Layer\Api
 */
class Collection implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $total;

    /**
     * Collection constructor.
     *
     * @param array $items
     * @param int   $total
     */
    public function __construct(array $items, $total = null)
    {
        $this->items = $items;
        $this->total = $total === null ? count($items) : $total;
    }

    /**
     * Return the collection items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Return the total number of elements for the API query
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Returns the of elements in the collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Retrieve an external iterator for the elements in the collection
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \OutOfBoundsException("The requested entity does not exists in the collection");
        }

        return $this->items[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
