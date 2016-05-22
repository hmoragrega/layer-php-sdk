<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class Error
 *
 * Represents an API error response
 *
 * @package UglyGremlin\Layer\Model
 */
class Error extends AbstractEntity
{
    /**
     * The error message
     *
     * @var string
     */
    public $message;

    /**
     * The error code
     *
     * @var int
     */
    public $code;
}
