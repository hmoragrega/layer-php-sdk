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
        $this->expectEntity();
        $this->requestFactory->get('users/userId/identity', [])
            ->willReturn($this->request);

        $this->getOne('userId')
            ->shouldReturnAnInstanceOf('UglyGremlin\Layer\Model\Identity');
    }

    /**
     * @dataProvider createIdentityPayload
     */
    function it_can_create_an_identity_from_different_inputs($identity, $payload)
    {
        $this->requestFactory->post('users/userId/identity', $payload)
            ->willReturn($this->request);

        $this->create('userId', $identity);
    }

    /**
     * @dataProvider updateIdentityPayload
     */
    function it_can_update_an_identity_from_different_inputs($identity, $payload)
    {
        $this->requestFactory->patch('users/userId/identity', $payload)
            ->willReturn($this->request);

        $this->update('userId', $identity);
    }

    /**
     * @dataProvider createIdentityPayload
     */
    function it_can_replace_an_identity_from_different_inputs($identity, $payload)
    {
        $this->requestFactory->put('users/userId/identity', $payload)
            ->willReturn($this->request);

        $this->replace('userId', $identity);
    }

    function it_can_remove_an_identity_from_different_inputs()
    {
        $this->requestFactory->delete('users/userId/identity')
            ->willReturn($this->request);

        $this->remove('userId');
    }

    function createIdentityPayload()
    {
        $identity = new \stdClass();
        $identity->foo = 'bar';

        $payload = json_encode(['foo' => 'bar']);

        return [
            [['foo' => 'bar'], json_encode(['foo' => 'bar'])],  // From array
            [$identity, $payload],                              // From stdClass
            [$payload, $payload]                                // From string
        ];
    }

    function updateIdentityPayload()
    {
        $identity = new \stdClass();
        $identity->foo = 'bar';

        $payload = json_encode(['foo' => 'bar']);

        return [
            [['foo' => 'bar'], json_encode(['foo' => 'bar'])],  // From array
            [$identity, json_encode([$identity])],              // From stdClass
            [$payload, $payload]                                // From string
        ];
    }
}
