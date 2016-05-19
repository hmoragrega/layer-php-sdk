<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Model;

/**
 * Class RichContent
 *
 * @see https://developer.layer.com/docs/client/introduction#rich-content
 *
 * @package UglyGremlin\Layer\Model
 */
class RichContent extends AbstractEntity
{
    /**
     * A Layer ID to identify the Content.
     *
     * @var string
     */
    public $id;

    /**
     * URL from which the Rich Content can be downloaded.
     *
     * @var string
     */
    public $download_url;

    /**
     * Time at which the download_url will expire.
     *
     * @var \DateTimeImmutable
     */
    public $expiration;

    /**
     * URL for refreshing the download_url.
     *
     * @var RichContent
     */
    public $refresh_url;

    /**
     * Size of the Rich Content.
     *
     * @var int
     */
    public $size;

    /**
     * Gets the "expiration" as date/time
     *
     * @return \DateTimeImmutable
     */
    public function getExpiration()
    {
        return new \DateTimeImmutable("@".strtotime($this->expiration));
    }

    /**
     * Check if the rich content has expired
     *
     * @return bool
     */
    public function hasExpired()
    {
        return time() >= $this->getExpiration()->getTimestamp();
    }
}
