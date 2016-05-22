<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Http;

use PhpSpec\ObjectBehavior;

/**
 * Class RequestFactory
 *
 * @package UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Http\RequestFactory
 */
class RequestFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Http\RequestFactory');
    }

    /**
     * @dataProvider requestTypes
     */
    function it_can_create_requests($method, $verb)
    {
        $request = $this->$method('url', ['foo' => 'bar'], 'body');

        $request->shouldBeAnInstanceOf('GuzzleHttp\Psr7\Request');
        $request->getMethod()->shouldBe($verb);
        $request->getUri()->__toString()->shouldBe('url');
        $request->getHeader('foo')->shouldBe(['bar']);
        $request->getBody()->getContents()->shouldBe('body');
    }
    
    function requestTypes()
    {
        return [
            ['get',    'GET'],
            ['post',   'POST'],
            ['put',    'PUT'],
            ['patch',  'PATCH'],
            ['delete', 'DELETE'],
        ];
    }
}
