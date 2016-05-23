<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use PhpSpec\ObjectBehavior;
use UglyGremlin\Layer\Api\Config;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class RequestFactorySpec
 *
 * @package UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\RequestFactory
 */
class RequestFactorySpec extends ObjectBehavior
{
    function let(Config $config, UuidGeneratorInterface $uuidGenerator)
    {
        $config->getAppId()->willReturn('appId');
        $config->getAppToken()->willReturn('appToken');
        $config->getBaseUrl()->willReturn('baseUrl/');
        $uuidGenerator->getUniqueId()->willReturn('uuid');

        $this->beConstructedWith($config, $uuidGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\RequestFactory');
    }

    function it_can_create_api_requests()
    {
        $request = $this->create('FOO', 'path', 'payload');

        $request->shouldBeAnInstanceOf('GuzzleHttp\Psr7\Request');
        $request->getMethod()->shouldBe('FOO');
        $request->getBody()->getContents()->shouldBe('payload');
        $request->getUri()->__toString()->shouldBe('baseUrl/apps/appId/path');
        $request->getHeaders()->shouldBe([
            'Accept'        => ['application/vnd.layer+json; version=1.1'],
            'Authorization' => ['Bearer appToken'],
            'Content-Type'  => ['application/json'],
            'User-Agent'    => ['UglyGremlin\'s Layer PHP SDK. 1.0.0'],
            'If-None-Match' => ['uuid'],
        ]);
    }

    function it_can_create_api_patch_requests()
    {
        $request = $this->create('PATCH', 'path', new \stdClass());

        $request->shouldBeAnInstanceOf('GuzzleHttp\Psr7\Request');
        $request->getMethod()->shouldBe('PATCH');
        $request->getBody()->getContents()->shouldBe('{}');
        $request->getUri()->__toString()->shouldBe('baseUrl/apps/appId/path');
        $request->getHeaders()->shouldBe([
            'Accept'                 => ['application/vnd.layer+json; version=1.1'],
            'Authorization'          => ['Bearer appToken'],
            'Content-Type'           => ['application/vnd.layer-patch+json'],
            'User-Agent'             => ['UglyGremlin\'s Layer PHP SDK. 1.0.0'],
            'If-None-Match'          => ['uuid'],
            'X-HTTP-Method-Override' => ['PATCH'],
        ]);
    }
}
