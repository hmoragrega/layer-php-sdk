<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

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
            'https://api.layer.com/apps/appId/users/userId/conversations?sort_by=last_message&page_size=50&fromId=fromId',
            $this->getHeaders()
        )->willReturn($this->request);

        $this->getByLastMessage('userId', 50, 'fromId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Collection');
    }

    function it_can_query_the_latest_conversations_by_creation_date()
    {
        $this->expectCollection();
        $this->requestFactory->get(
            'https://api.layer.com/apps/appId/users/userId/conversations?sort_by=created_at',
            $this->getHeaders()
        )->willReturn($this->request);

        $this->getByCreationDate('userId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Collection');
    }

    function it_can_get_one_conversation_as_user_perspective()
    {
        $this->expectEntity();
        $this->requestFactory->get(
            'https://api.layer.com/apps/appId/users/userId/conversations/conversationId',
            $this->getHeaders()
        )->willReturn($this->request);

        $this->getOneAsUser('userId', 'conversationId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Conversation');
    }

    function it_can_get_one_conversation_as_system_perspective()
    {
        $this->expectEntity();
        $this->requestFactory->get(
            'https://api.layer.com/apps/appId/conversations/conversationId',
            $this->getHeaders()
        )->willReturn($this->request);

        $this->getOneAsSystem('conversationId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Conversation');
    }
}
