<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class MessagePart
 *
 * @see https://developer.layer.com/docs/client/introduction#message-part
 *
 * @package UglyGremlin\Layer\Model
 */
class MessagePart extends AbstractEntity
{
    /**
     * A Layer ID to identify the Part.
     *
     * @var string
     */
    public $id;

    /**
     * The Mime Type (text/plain; image/png; etc...).
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-mime_type-code-property
     *
     * @var string
     */
    public $mime_type;

    /**
     * The contents ("Hello world", "please end with etc", etc...).
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-body-code-property
     *
     * @var string
     */
    public $body;

    /**
     * Optional encoding field; "base64".
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-encoding-code-property
     *
     * @var string
     */
    public $encoding;

    /**
     * A Rich Content object for larger payloads.
     *
     * @see https://developer.layer.com/docs/client/introduction#the-code-content-code-property
     *
     * @var RichContent
     */
    public $content;

    /**
     * {@inheritDoc}
     */
    protected function map($property, $value)
    {
        if ($property == 'content' && $value !== null) {
            return new RichContent($value);
        }

        return $value;
    }
}
