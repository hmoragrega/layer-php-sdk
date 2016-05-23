<?php

namespace spec\UglyGremlin\Layer;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class ClientBuilderSpec
 *
 * @package spec\UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\ClientBuilder
 */
class ClientBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('appId', 'appToken', 'baseUrl');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\ClientBuilder');
    }

    function it_can_build_the_client(ClientInterface $httpClient, UuidGeneratorInterface $uuidGenerator)
    {
        $this->withHttpClient($httpClient);
        $this->withUuidGenerator($uuidGenerator);

        $this->build()->shouldReturnAnInstanceOf('UglyGremlin\Layer\Client');
    }

    function it_can_build_the_client_with_ramsey_uuid_generator(ClientInterface $httpClient)
    {
        if (!class_exists('Ramsey\Uuid\Uuid') && !class_exists('Rhumsaa\Uuid\Uuid')) {
            throw new SkippingException('The require dependency is not installed');
        }

        $this->withHttpClient($httpClient);

        $this->build()->shouldReturnAnInstanceOf('UglyGremlin\Layer\Client');
    }

    function it_can_build_the_client_with_guzzle(UuidGeneratorInterface $uuidGenerator)
    {
        if (!class_exists('Guzzle\Http\Client') && !class_exists('GuzzleHttp\Client')) {
            throw new SkippingException('The require dependency is not installed');
        }

        $this->withUuidGenerator($uuidGenerator);

        $this->build()->shouldReturnAnInstanceOf('UglyGremlin\Layer\Client');
    }
}
