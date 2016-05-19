<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Uuid\Generator;

use PhpSpec\ObjectBehavior;

/**
 * Class RhumsaaUuidGeneratorSpec
 *
 * @package spec\UglyGremlin\Layer\UniqueId
 * @require Rhumsaa\Uuid\Uuid
 * @mixin \UglyGremlin\Layer\Uuid\Generator\RhumsaaUuidGenerator
 */
class RhumsaaUuidGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Uuid\Generator\RhumsaaUuidGenerator');
        $this->shouldImplement('UglyGremlin\Layer\Uuid\UuidGeneratorInterface');
    }

    function it_generates_unique_ids()
    {
        $this->getUniqueId()->shouldMatch('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/');
    }
}
