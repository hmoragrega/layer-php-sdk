<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use PhpSpec\ObjectBehavior;

/**
 * Class CollectionResponseSpec
 *
 * @package spec\UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\CollectionResponse
 */
class CollectionResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([1, 2, 3], 100);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\CollectionResponse');
    }

    function it_can_return_the_list_of_items()
    {
        $this->getList()->shouldReturn([1, 2, 3]);
    }

    function it_can_return_the_total_items()
    {
        $this->getTotal()->shouldReturn(100);
    }
}
