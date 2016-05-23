<?php

namespace spec\UglyGremlin\Layer;

use PhpSpec\ObjectBehavior;
use UglyGremlin\Layer\Api\Config;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Log\Logger;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class LayerClientSpec
 *
 * @package spec\UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Client
 */
class ClientSpec extends ObjectBehavior
{
    function let(Config $config, ClientInterface $httpClient, UuidGeneratorInterface $uuidGenerator, Logger $logger)
    {
        $this->beConstructedWith($config, $httpClient, $uuidGenerator, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Client');
    }

    function it_can_return_the_conversations_api()
    {
        $this->conversations()->shouldBeAnInstanceOf('UglyGremlin\Layer\Api\ConversationApi');
    }

    function it_can_return_the_messages_api()
    {
        $this->messages()->shouldBeAnInstanceOf('UglyGremlin\Layer\Api\MessageApi');
    }

    function it_can_return_the_identities_api()
    {
        $this->identities()->shouldBeAnInstanceOf('UglyGremlin\Layer\Api\IdentityApi');
    }
}
