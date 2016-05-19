<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Uuid\Generator;

use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class RamseyUuidGenerator
 *
 * @package UglyGremlin\Layer\UniqueId
 */
class RamseyUuidGenerator implements UuidGeneratorInterface
{
    /**
     * Generates a unique id
     *
     * @return string
     */
    public function getUniqueId()
    {
        $uuid4 = Uuid::uuid4();

        return $uuid4->toString();
    }
}
