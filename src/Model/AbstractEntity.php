<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

use UglyGremlin\Layer\Api\CollectionResponse;

/**
 * Class Entity
 *
 * @package UglyGremlin\Layer\Model
 */
abstract class AbstractEntity extends \stdClass
{
    /**
     * Creates a model
     *
     * @param array|\stdClass|null $source
     */
    public function __construct($source = null)
    {
        if (is_array($source) || $source instanceof \Traversable) {
            foreach ($source as $key => $value) {
                $this->{$key} = $this->map($key, $value);
            }
        }
    }

    /**
     * Creates a collection of
     *
     * @param \stdClass[] $entities
     * @param int|null    $total
     *
     * @return Collection
     */
    public static function collection(array $entities = null, $total = null)
    {
        $collection = [];

        if (is_array($entities)) {
            foreach ($entities as $entity) {
                $collection[] = new static($entity);
            }
        }

        return new Collection($collection, $total);
    }

    /**
     * Builds a collection from an API response
     *
     * @param CollectionResponse $response
     *
     * @return Collection
     */
    public static function fromCollectionResponse(CollectionResponse $response)
    {
        return self::collection($response->getList(), $response->getTotal());
    }

    /**
     * Perform custom transformations on specific properties
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function map($property, $value)
    {
        return $value;
    }
}
