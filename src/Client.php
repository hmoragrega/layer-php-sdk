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
use UglyGremlin\Layer\Api\ResponseParser;
use UglyGremlin\Layer\Api\ResponseValidator;
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
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ResponseValidator
     */
    private $responseValidator;

    /**
     * @var ResponseParser
     */
    private $responseParser;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Client constructor.
     *
     * @param ClientInterface        $httpClient
     * @param UuidGeneratorInterface $uuidGenerator
     * @param Config                 $config
     * @param Logger                 $logger
     */
    public function __construct(
        Config $config,
        ClientInterface $httpClient,
        UuidGeneratorInterface $uuidGenerator,
        Logger $logger
    ) {
        $this->requestFactory    = new RequestFactory($config, $uuidGenerator);
        $this->responseParser    = new ResponseParser();
        $this->responseValidator = new ResponseValidator($this->responseParser);
        $this->httpClient        = $httpClient;
        $this->logger            = $logger;
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
                $this->responseValidator,
                $this->responseParser,
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
                $this->responseValidator,
                $this->responseParser,
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
                $this->responseValidator,
                $this->responseParser,
                $this->logger
            );
        }

        return $this->identities;
    }
}
