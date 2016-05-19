<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class Conversation
 *
 * @see https://developer.layer.com/docs/client/introduction#the-conversation-object
 *
 * @package UglyGremlin\Layer\Model
 */
class Conversation extends AbstractEntity
{
    /**
     * A Layer ID to identify the conversation.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-id-code-property
     *
     * @var string
     */
    public $id;

    /**
     * A URL for accessing the Conversation via the REST API.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-url-code-property
     *
     * @var string
     */
    public $url;

    /**
     * A URL for accessing the messages of the Conversation via the REST API.
     *
     * @var string
     */
    public $messages_url;

    /**
     * An formatted date/time indicating when the Conversation was created on the server.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-created_at-code-property
     *
     * @var \DateTimeImmutable
     */
    public $created_at;

    /**
     * A Message Object representing the last message sent within this Conversation.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-last_message-code-property
     *
     * @var Message
     */
    public $last_message;

    /**
     * Array of User IDs indicating who is currently participating in a Conversation.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-participants-code-property
     *
     * @var array
     */
    public $participants = [];

    /**
     * True if this is the only Distinct Conversation shared amongst these participants
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-distinct-code-property
     *
     * @var bool
     */
    public $distinct;

    /**
     * True if this is the only Distinct Conversation shared amongst these participants.
     *
     * @var integer
     */
    public $unread_message_count;

    /**
     * Custom data associated with the Conversation that is viewable/editable by all participants of the Conversation
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-metadata-code-property
     *
     * @var \stdClass
     */
    public $metadata;

    /**
     * Gets the "created_at" as date/time
     *
     * @return \DateTimeImmutable
     */
    public function getCreatedAt()
    {
        return new \DateTimeImmutable("@".strtotime($this->created_at));
    }

    /**
     * {@inheritDoc}
     */
    protected function map($property, $value)
    {
        if ($property == 'last_message' && $value) {
            return new Message($value);
        }

        return $value;
    }
}
