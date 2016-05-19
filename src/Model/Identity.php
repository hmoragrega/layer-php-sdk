<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class Identity
 *
 * @see https://developer.layer.com/docs/platform/users#the-identity-object
 *
 * @package UglyGremlin\Layer\Model
 */
class Identity extends AbstractEntity
{
    /**
     * The user id.
     *
     * @var string
     */
    public $user_id;

    /**
     * The name to render when displaying this user. Max length: 128 characters.
     *
     * @var string
     */
    public $display_name;

    /**
     * A URL to an image. Max length: 1024 characters.
     *
     * @var string
     */
    public $avatar_url;

    /**
     * The user's first name. Max length: 128 characters.
     *
     * @var string
     */
    public $first_name;

    /**
     * The user's last name. Max length: 128 characters.
     *
     * @var string
     */
    public $last_name;

    /**
     * The user's phone number. Typically usage expects this be a cellphone number for use with SMS services,
     * but actual usage depends upon the application. Max length: 32 characters.
     *
     * @var string
     */
    public $phone_number;

    /**
     * The user's email address. Max length: 255 characters.
     *
     * @var string
     */
    public $email_address;

    /**
     * Public encryption key.
     *
     * @var string
     */
    public $public_key;

    /**
     * A set of name value pairs. Values must be strings. A maximum of 16 name value pairs allowed.
     * Unlike Conversation metadata, sub-objects are not supported. Maximum keys: 16 pairs.
     *
     * @var array
     */
    public $metadata;
}
