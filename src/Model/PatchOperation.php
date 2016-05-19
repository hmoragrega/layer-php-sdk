<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class PatchOperation
 *
 * @see https://github.com/layerhq/layer-patch
 *
 * @package UglyGremlin\Layer\Model
 */
class PatchOperation extends AbstractEntity
{
    /**
     * Type of mutation to perform (required).
     *
     * @see https://github.com/layerhq/layer-patch#the-operation-key
     *
     * @var string
     */
    public $operation;

    /**
     * Path to the property to modify (required).
     *
     * A property identifies either a property of the Base Object or a . separated path to a key within one of
     * its properties.
     *
     * @see https://github.com/layerhq/layer-patch#the-property-key
     *
     * @var string
     */
    public $property;

    /**
     * Value to be added or removed from the property.
     *
     * https://github.com/layerhq/layer-patch#the-value-and-id-key
     *
     * @var string
     */
    public $value;

    /**
     * Id of the object to be added or removed from the property.
     *
     * @see https://github.com/layerhq/layer-patch#the-value-and-id-key
     *
     * @var string
     */
    public $id;

    /**
     * Index in an array of a value to insert or remove.
     *
     * @see https://github.com/layerhq/layer-patch#the-index-key
     *
     * @var string
     */
    public $index;
}
