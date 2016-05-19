<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer;

use UglyGremlin\Layer\Api\Config;
use UglyGremlin\Layer\Api\ConversationApi;
use UglyGremlin\Layer\Api\IdentityApi;
use UglyGremlin\Layer\Api\MessageApi;
use UglyGremlin\Layer\Api\RequestFactory;
use UglyGremlin\Layer\Api\ResponseChecker;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Log\Logger;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class Client
 *
 * @package UglyGremlin\Layer\Api
 */
class Client
{
    /**
     * @var ConversationApi
     */
    private $conversations;

    /**
     * @var MessageApi
     */
    private $messages;

    /**
     * @var IdentityApi
     */
    private $identities;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var ResponseChecker
     */
    private $checker;

    /**
     * @var UuidGeneratorInterface
     */
    private $uuidGenerator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Client constructor.
     *
     * @param ClientInterface        $httpClient
     * @param ResponseChecker        $checker
     * @param UuidGeneratorInterface $uuidGenerator
     * @param Config                 $config
     * @param Logger                 $logger
     */
    public function __construct(
        ClientInterface $httpClient,
        ResponseChecker $checker,
        UuidGeneratorInterface $uuidGenerator,
        Config $config,
        Logger $logger
    ) {
        $this->httpClient    = $httpClient;
        $this->checker       = $checker;
        $this->uuidGenerator = $uuidGenerator;
        $this->config        = $config;
        $this->logger        = $logger;
    }

    /**
     * Returns the conversations API
     *
     * @return ConversationApi
     */
    public function conversations()
    {
        if (!$this->conversations instanceof ConversationApi) {
            $this->conversations = new ConversationApi(
                $this->httpClient,
                $this->checker,
                $this->uuidGenerator,
                $this->config,
                $this->logger
            );
        }

        return $this->conversations;
    }

    /**
     * Returns the messages API
     *
     * @return MessageApi
     */
    public function messages()
    {
        if (!$this->messages instanceof MessageApi) {
            $this->messages = new MessageApi(
                $this->httpClient,
                $this->checker,
                $this->uuidGenerator,
                $this->config,
                $this->logger
            );
        }

        return $this->messages;
    }

    /**
     * Returns the identities API
     *
     * @return IdentityApi
     */
    public function identities()
    {
        if (!$this->identities instanceof IdentityApi) {
            $this->identities = new IdentityApi(
                $this->httpClient,
                $this->checker,
                $this->uuidGenerator,
                $this->config,
                $this->logger
            );
        }

        return $this->identities;
    }
}
