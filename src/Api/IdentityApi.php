<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Api;

use UglyGremlin\Layer\Model\Identity;

/**
 * Class IdentityApi
 *
 * @package UglyGremlin\Layer\Api
 */
class IdentityApi extends AbstractApi
{
    /**
     * Gets an identity
     *
     * @see https://developer.layer.com/docs/platform/users#retrieve-an-identity
     *
     * @param string $userId
     *
     * @return \UglyGremlin\Layer\Model\Identity
     */
    public function getOne($userId)
    {
        return new Identity($this->getEntity("users/$userId/identity"));
    }

    /**
     * Creates an identity
     *
     * @see https://developer.layer.com/docs/platform/users#create-an-identity
     *
     * @param string                $userId
     * @param array|stdClass|string $identity
     *
     * @return stdClass
     */
    public function create($userId, $identity)
    {
        $this->post("users/$userId/identity", $identity);
    }

    /**
     * Updates an identity
     *
     * @see https://developer.layer.com/docs/platform/users#update-an-identity
     *
     * @param string                 $userId
     * @param \stdClass|array|string $payload
     */
    public function update($userId, $payload)
    {
        if ($payload instanceof \stdClass) {
            $payload = [$payload];
        }

        $this->patch("users/$userId/identity", $payload);
    }

    /**
     * Replaces an identity
     *
     * @see https://developer.layer.com/docs/platform/users#replace-an-identity
     *
     * @param string                $userId
     * @param array|stdClass|string $identity
     */
    public function replace($userId, $identity)
    {
        $this->put("users/$userId/identity", $identity);
    }

    /**
     * Deletes an identity
     *
     * @see https://developer.layer.com/docs/platform/users#create-an-identity
     *
     * @param string $userId
     */
    public function remove($userId)
    {
        $this->delete("users/$userId/identity");
    }
}
