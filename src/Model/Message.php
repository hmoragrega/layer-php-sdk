<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class Message
 *
 * @see https://developer.layer.com/docs/client/introduction#the-message-object
 *
 * @package UglyGremlin\Layer\Model
 */
class Message extends AbstractEntity
{
    /**
     * A Layer ID to identify the message.
     *
     * @var string
     */
    public $id;

    /**
     * A URL for accessing the Message via the REST API.
     *
     * @var string
     */
    public $url;

    /**
     * A URL for accessing the receipts of the Message via the REST API.
     *
     * @var string
     */
    public $receipts_url;

    /**
     * Position of the Message within the Conversation.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-position-code-property
     *
     * @var int
     */
    public $position;

    /**
     * Object to identify the conversation of the message.
     *
     * It has 2 keys:
     * - id  Id of the conversation.
     * - url URL for accessing the conversation via the REST API.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-conversation-code-property
     *
     * @var \stdClass
     */
    public $conversation;

    /**
     * Each MessagePart in the parts array contains a part of the contents of the message
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-parts-code-property
     *
     * @var MessagePart[]
     */
    public $parts = [];

    /**
     * Date/time that the message was sent.
     *
     * @var \DateTimeImmutable
     */
    public $sent_at;

    /**
     * Identifies who sent the message
     *
     * It has 2 keys:
     * - user_id  The user_id is the ID of the participant that sent the message.
     * - name     If sent by the Platform API, the name is a system name such as "Administrator" or "Moderator"
     *             and is used instead of user_id
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-sender-code-property
     *
     * @var \stdClass
     */
    public $sender;

    /**
     * Indicates if the user has read the Message.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-is_unread-code-property
     *
     * @var bool
     */
    public $is_unread;

    /**
     * Hash of User IDs indicating which users have received/read the message.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-recipient_status-code-property
     *
     * @var \stdClass
     */
    public $recipient_status;

    /**
     * Gets the "sent_at" as date/time
     *
     * @return \DateTimeImmutable
     */
    public function getSentAt()
    {
        return new \DateTimeImmutable("@".strtotime($this->sent_at));
    }

    /**
     * {@inheritDoc}
     */
    protected function map($property, $value)
    {
        if ($property == 'parts') {
            return MessagePart::collection($value)->getItems();
        }

        return $value;
    }
}
