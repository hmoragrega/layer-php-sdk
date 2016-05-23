<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use UglyGremlin\Layer\Model\Message;

/**
 * Class MessageApi
 *
 * @package UglyGremlin\Layer\Api
 */
class MessageApi extends AbstractCollectionProviderApi
{
    /**
     * @param string      $userId
     * @param string      $conversationId
     * @param null|int    $limit
     * @param null|string $fromId
     *
     * @return \UglyGremlin\Layer\Model\Collection
     */
    public function getByConversationAsUser($userId, $conversationId, $limit = null, $fromId = null)
    {
        list($items, $total) = $this->query("users/$userId/conversations/$conversationId/messages", $limit, $fromId);

        return Message::collection($items, $total);
    }

    /**
     * @param string      $conversationId
     * @param null|int    $limit
     * @param null|string $fromId
     *
     * @return \UglyGremlin\Layer\Model\Collection
     */
    public function getByConversationAsSystem($conversationId, $limit = null, $fromId = null)
    {
        list($items, $total) = $this->query("conversations/$conversationId/messages", $limit, $fromId);

        return Message::collection($items, $total);
    }

    /**
     * @param string $userId
     * @param string $messageId
     *
     * @return \stdClass
     */
    public function getOneAsUser($userId, $messageId)
    {
        return new Message($this->getEntity("users/$userId/messages/$messageId"));
    }

    /**
     * @param string $conversationId
     * @param string $messageId
     *
     * @return \stdClass
     */
    public function getOneAsSystem($conversationId, $messageId)
    {
        return new Message($this->getEntity("conversations/$conversationId/messages/$messageId"));
    }
}
