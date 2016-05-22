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
        list($items, $total) = $this->query($this->path($userId, $conversationId), $limit, $fromId);

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
        list($items, $total) = $this->query($this->path(null, $conversationId), $limit, $fromId);

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
        return new Message((array) $this->getEntity($this->path($userId, null, $messageId)));
    }

    /**
     * @param string $conversationId
     * @param string $messageId
     *
     * @return \stdClass
     */
    public function getOneAsSystem($conversationId, $messageId)
    {
        return new Message((array) $this->getEntity($this->path(null, $conversationId, $messageId)));
    }

    /**
     * Returns the API url path
     *
     * @param string|null $userId
     * @param string|null $conversationId
     * @param string|null $messageId
     *
     * @return string
     */
    private function path($userId = null, $conversationId = null, $messageId = null)
    {
        $path = "";

        if ($userId !== null) {
            $path = "users/$userId/";
        }

        if ($conversationId !== null) {
            $path .= "conversations/$conversationId/";
        }

        $path .= "messages";

        if ($messageId !== null) {
            $path .= "/$messageId";
        }

        return $path;
    }
}
