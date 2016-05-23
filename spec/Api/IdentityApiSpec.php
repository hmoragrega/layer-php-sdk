<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace spec\UglyGremlin\Layer\Api;

use Prophecy\Argument;

/**
 * Class MessageApi
 *
 * @package spec\UglyGremlin\Layer\Api
 * @mixin \UglyGremlin\Layer\Api\IdentityApi
 */
class IdentityApiSpec extends AbstractApiSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('UglyGremlin\Layer\Api\IdentityApi');
    }

    function it_can_get_one_message_as_user_perspective()
    {
        $this->responseParser->parseObject($this->exchange)
            ->willReturn(new \stdClass());

        $this->requestFactory->create('GET', 'users/userId/identity', null)
            ->willReturn($this->request);

        $this->getOne('userId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Identity');
    }

    function it_can_create_an_identity()
    {
        $this->requestFactory->create('POST', 'users/userId/identity', 'payload')
            ->willReturn($this->request);

        $this->create('userId', 'payload');
    }

    function it_can_update_an_identity()
    {
        $patchOperation = new \stdClass();

        $this->requestFactory->create('PATCH', 'users/userId/identity', [$patchOperation])
            ->willReturn($this->request);

        $this->update('userId', $patchOperation);
    }

    function it_can_replace_an_identity()
    {
        $this->requestFactory->create('PUT', 'users/userId/identity', 'payload')
            ->willReturn($this->request);

        $this->replace('userId', 'payload');
    }

    function it_can_remove_an_identity()
    {
        $this->requestFactory->create('DELETE', 'users/userId/identity', null)
            ->willReturn($this->request);

        $this->remove('userId');
    }
}
