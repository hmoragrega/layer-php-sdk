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
    public function __construct(array $source = [])
    {
        foreach ($source as $key => $value) {
            $this->{$key} = $this->map($key, $value);
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
    public static function collection(array $entities = [], $total = null)
    {
        $collection = [];
        foreach ($entities as $entity) {
            $collection[] = new static((array) $entity);
        }

        return new Collection($collection, $total);
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
