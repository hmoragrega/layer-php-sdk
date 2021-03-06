<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Uuid\Generator;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;

/**
 * Class RamseyUuidGeneratorSpec
 *
 * @package spec\UglyGremlin\Layer\Uuid\Generator
 * @mixin \UglyGremlin\Layer\Uuid\Generator\RamseyUuidGenerator
 */
class RamseyUuidGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        if (!class_exists('Ramsey\Uuid\Uuid') && !class_exists('Rhumsaa\Uuid\Uuid')) {
            throw new SkippingException('The required dependency is not installed');
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Uuid\Generator\RamseyUuidGenerator');
        $this->shouldImplement('UglyGremlin\Layer\Uuid\UuidGeneratorInterface');
    }

    function it_generates_unique_ids()
    {
        $this->getUniqueId()->shouldMatch('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/');
    }
}
