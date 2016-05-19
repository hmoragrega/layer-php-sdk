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
        return new Identity($this->getEntity($this->path($userId)));
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
        $this->post($this->path($userId), $identity);
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

        $this->patch($this->path($userId), $payload);
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
        $this->put($this->path($userId), $identity);
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
        $this->delete($this->path($userId));
    }

    /**
     * Returns the path for the identity API
     *
     * @param string $userId
     *
     * @return string
     */
    private function path($userId)
    {
        return "users/$userId/identity";
    }
}
