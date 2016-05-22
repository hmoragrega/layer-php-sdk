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
 * Class ConfigSpec
 *
 * @package UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\Config
 */
class ConfigSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('appId', 'appToken', 'http://base.url');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\Config');
    }

    function it_can_return_the_app_id()
    {
        $this->getAppId()->shouldReturn('appId');
    }

    function it_can_return_the_app_token()
    {
        $this->getAppToken()->shouldReturn('appToken');
    }

    function it_can_return_the_api_base_url()
    {
        $this->getBaseUrl()->shouldReturn('http://base.url');
    }
}
