<?php

namespace spec\UglyGremlin\Layer;

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
}
