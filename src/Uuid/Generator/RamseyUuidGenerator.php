<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Uuid\Generator;

use UglyGremlin\Layer\Exception\RuntimeException;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class RamseyUuidGenerator
 *
 * @package UglyGremlin\Layer\UniqueId
 */
class RamseyUuidGenerator implements UuidGeneratorInterface
{
    const RAMSEY  = 'Ramsey\Uuid\Uuid';
    const RHUMSAA = 'Rhumsaa\Uuid\Uuid';
    const METHOD  = 'uuid4';

    /**
     * Loaded class
     *
     * @var string
     */
    private $class;

    /**
     * RamseyUuidGenerator constructor.
     */
    public function __construct()
    {
        if (class_exists(self::RAMSEY)) {
            $this->class = self::RAMSEY;
        } elseif (class_exists(self::RHUMSAA)) {
            $this->class = self::RHUMSAA;
        } else {
            throw new RuntimeException("There is no valid Ramsey or Rhumsaa package installed");
        }
    }

    /**
     * Generates a unique id
     *
     * @return string
     */
    public function getUniqueId()
    {
        return call_user_func([$this->class, self::METHOD])->toString();
    }
}
