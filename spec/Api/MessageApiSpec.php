<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use Prophecy\Argument;

/**
 * Class MessageApi
 *
 * @package spec\UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\MessageApi
 */
class MessageApiSpec extends AbstractApiSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\MessageApi');
    }

    function it_can_query_the_messages_from_a_conversation_from_user_perspective()
    {
        $this->responseParser->parseList($this->exchange)
            ->willReturn([[new \stdClass()], 100]);

        $this->requestFactory->create('GET', 'users/userId/conversations/conversationId/messages', null)
            ->willReturn($this->request);

        $this->getByConversationAsUser('userId', 'conversationId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Collection');
    }

    function it_can_query_the_messages_from_a_conversation_from_system_perspective()
    {
        $this->responseParser->parseList($this->exchange)
            ->willReturn([[new \stdClass()], 100]);

        $this->requestFactory->create('GET', 'conversations/conversationId/messages', null)
            ->willReturn($this->request);

        $this->getByConversationAsSystem('conversationId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Collection');
    }

    function it_can_get_one_message_as_user_perspective()
    {
        $this->responseParser->parseObject($this->exchange)
            ->willReturn(new \stdClass());

        $this->requestFactory->create('GET', 'users/userId/messages/messageId', null)
            ->willReturn($this->request);

        $this->getOneAsUser('userId', 'messageId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Message');
    }

    function it_can_get_one_message_as_system_perspective()
    {
        $this->responseParser->parseObject($this->exchange)
            ->willReturn(new \stdClass());

        $this->requestFactory->create('GET', 'conversations/conversationId/messages/messageId', null)
            ->willReturn($this->request);

        $this->getOneAsSystem('conversationId', 'messageId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Message');
    }
}
