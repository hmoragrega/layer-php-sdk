<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer;

use UglyGremlin\Layer\Api\ConversationApi;
use UglyGremlin\Layer\Api\IdentityApi;
use UglyGremlin\Layer\Api\MessageApi;
use UglyGremlin\Layer\Api\RequestFactory;
use UglyGremlin\Layer\Api\ResponseChecker;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Log\Logger;

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
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ResponseChecker
     */
    private $checker;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Client constructor.
     *
     * @param ClientInterface $httpClient
     * @param RequestFactory  $requestFactory
     * @param ResponseChecker $checker
     * @param Logger          $logger
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactory $requestFactory,
        ResponseChecker $checker,
        Logger $logger
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->checker = $checker;
        $this->logger = $logger;
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
                $this->requestFactory,
                $this->checker,
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
                $this->requestFactory,
                $this->checker,
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
                $this->requestFactory,
                $this->checker,
                $this->logger
            );
        }

        return $this->identities;
    }
}
