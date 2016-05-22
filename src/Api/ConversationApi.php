<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use UglyGremlin\Layer\Model\Conversation;

/**
 * Class ConversationApi
 *
 * @package UglyGremlin\Layer\Api
 */
class ConversationApi extends AbstractCollectionProviderApi
{
    /**
     * Retrieves the latest conversations sorted by creation date in descending order
     *
     * @param string $userId
     * @param int    $limit  Default and maximum is 100
     * @param null   $fromId
     *
     * @return \UglyGremlin\Layer\Model\Collection
     */
    public function getByCreationDate($userId, $limit = null, $fromId = null)
    {
        list($items, $total) = $this->query($this->path($userId), $limit, $fromId, ['sort_by' => 'created_at']);

        return Conversation::collection($items, $total);
    }

    /**
     * Retrieves the latest conversations sorted by the last message date in descending order
     *
     * @param string $userId
     * @param int    $limit  Default and maximum is 100
     * @param null   $fromId
     *
     * @return \UglyGremlin\Layer\Model\Collection
     */
    public function getByLastMessage($userId, $limit = 100, $fromId = null)
    {
        list($items, $total) = $this->query($this->path($userId), $limit, $fromId, ['sort_by' => 'last_message']);

        return Conversation::collection($items, $total);
    }

    /**
     * https://developer.layer.com/docs/platform/conversations#retrieve-one-conversation-user-perspective-
     *
     * @param string $userId
     * @param string $conversationId
     *
     * @return Conversation
     */
    public function getOneAsUser($userId, $conversationId)
    {
        return new Conversation($this->getEntity($this->path($userId, $conversationId)));
    }

    /**
     * Retrieves one conversation from system perspective
     *
     * @see https://developer.layer.com/docs/platform/conversations#retrieve-one-conversation-system-perspective-
     *
     * @param string $conversationId
     *
     * @return Conversation
     */
    public function getOneAsSystem($conversationId)
    {
        return new Conversation($this->getEntity($this->path(null, $conversationId)));
    }

    /**
     * Returns the API url path
     *
     * @param string|null $userId
     * @param string|null $conversationId
     *
     * @return string
     */
    private function path($userId = null, $conversationId = null)
    {
        $path = "";

        if ($userId !== null) {
            $path = "users/$userId/";
        }

        $path .= "conversations";

        if ($conversationId !== null) {
            $path .= "/$conversationId";
        }

        return $path;
    }
}
