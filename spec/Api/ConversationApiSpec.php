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
 * Class ConversationApi
 *
 * @package spec\UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\ConversationApi
 */
class ConversationApiSpec extends AbstractApiSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\ConversationApi');
    }

    function it_can_query_the_latest_conversations_by_message()
    {
        $this->expectCollection();
        $this->requestFactory->get(
            'users/userId/conversations',
            Argument::allOf(
                Argument::withEntry("sort_by", "last_message"),
                Argument::withEntry("page_size", 50),
                Argument::withEntry("fromId", "fromId")
            )
        )->willReturn($this->request);

        $this->getByLastMessage('userId', 50, 'fromId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Collection');
    }

    function it_can_query_the_latest_conversations_by_creation_date()
    {
        $this->expectCollection();
        $this->requestFactory->get('users/userId/conversations', Argument::withEntry("sort_by", "created_at"))
            ->willReturn($this->request);

        $this->getByCreationDate('userId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Collection');
    }

    function it_can_get_one_conversation_as_user_perspective()
    {
        $this->expectEntity();
        $this->requestFactory->get('users/userId/conversations/conversationId', [])
            ->willReturn($this->request);

        $this->getOneAsUser('userId', 'conversationId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Conversation');
    }

    function it_can_get_one_conversation_as_system_perspective()
    {
        $this->expectEntity();
        $this->requestFactory->get('conversations/conversationId', [])
            ->willReturn($this->request);

        $this->getOneAsSystem('conversationId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Conversation');
    }
}
