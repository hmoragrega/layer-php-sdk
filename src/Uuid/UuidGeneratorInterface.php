<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Uuid;

/**
 * Interface UuidGeneratorInterface
 *
 * Classes implementing this interface are able to generate RFC 4122-compliant unique ids
 *
 * @package UglyGremlin\Layer\Uuid
 */
interface UuidGeneratorInterface
{
    /**
     * Generates a RFC 4122-compliant unique id
     *
     * @return string
     */
    public function getUniqueId();
}
