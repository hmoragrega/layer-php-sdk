<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer;

use Guzzle\Http\Client as GuzzleClient;
use GuzzleHttp\Client as GuzzleHttpClient;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use UglyGremlin\Layer\Api\RequestFactory;
use UglyGremlin\Layer\Api\ResponseChecker;
use UglyGremlin\Layer\Exception\InvalidArgumentException;
use UglyGremlin\Layer\Exception\RuntimeException;
use UglyGremlin\Layer\Http\Client\CurlAdapter;
use UglyGremlin\Layer\Http\Client\GuzzleAdapter;
use UglyGremlin\Layer\Http\Client\GuzzleHttp;
use UglyGremlin\Layer\Http\Client\GuzzleHttpAdapter;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Log\Logger;
use UglyGremlin\Layer\Uuid\Generator\RamseyUuidGenerator;
use UglyGremlin\Layer\Uuid\Generator\RhumsaaUuidGenerator;
use UglyGremlin\Layer\Uuid\UuidGeneratorInterface;

/**
 * Class ClientBuilder
 *
 * @package UglyGremlin\Layer\Api
 */
class ClientBuilder
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $appToken;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * Request execution timeout in seconds
     *
     * @var ClientInterface|string|null
     */
    private $httpClient;

    /**
     * Request execution timeout in seconds
     *
     * @var UuidGeneratorInterface|string|null
     */
    private $uuidGenerator;

    /**
     * Request execution timeout in seconds
     *
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * Request execution timeout in seconds
     *
     * @var float
     */
    private $timeout = 0;

    /**
     * Request connection timeout in seconds
     *
     * @var float
     */
    private $connectionTimeout = 0;

    /**
     * ClientBuilder constructor.
     *
     * @param string $appId
     * @param string $appToken
     * @param string $baseUrl
     */
    private function __construct($appId, $appToken, $baseUrl)
    {
        $this->appId    = $appId;
        $this->appToken = $appToken;
        $this->baseUrl  = $baseUrl;
    }

    /**
     * Starts the build process
     *
     * @param string $appId    The application identifier
     * @param string $appToken The application token
     * @param string $baseUrl  The API base URL
     *
     * @return $this
     */
    public static function client($appId, $appToken, $baseUrl = RequestFactory::API_BASE_URL)
    {
        return new self($appId, $appToken, $baseUrl);
    }

    /**
     * This method will build the Layer API client
     *
     * For the HTTP client and the UUID generator you can pass a string to choose one of the built-in options or pass
     * a concrete implementation of the respective interfaces
     *
     * @return Client
     *
     * @throws InvalidArgumentException When one requested built-in classes does not exist
     * @throws RuntimeException         When there is a missing library or extension required to build the client
     */
    public function build()
    {
        $this->buildMissingDependencies();

        $requestFactory = new RequestFactory($this->uuidGenerator, $this->appId, $this->appToken, $this->baseUrl);
        $checker        = new ResponseChecker();
        $logger         = new Logger($this->logger);

        return new Client($this->httpClient, $requestFactory, $checker, $logger);
    }

    /**
     * Sets the request connection timeout in seconds
     *
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function withLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Sets the request connection timeout in seconds
     *
     * @param string|ClientInterface $httpClient The HTTP client
     *
     * @return $this
     */
    public function withHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Sets the request connection timeout in seconds
     *
     * @param string|UuidGeneratorInterface $uuidGenerator The UUID generator
     *
     * @return $this
     */
    public function withUuidGenerator($uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;

        return $this;
    }

    /**
     * Sets the request execution timeout in seconds
     *
     * @param float $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Sets the request connection timeout in seconds
     *
     * @param float $connectionTimeout
     *
     * @return $this
     */
    public function setConnectionTimeout($connectionTimeout)
    {
        $this->connectionTimeout = $connectionTimeout;

        return $this;
    }

    /**
     * Checks for missing dependencies and builds them
     */
    private function buildMissingDependencies()
    {
        if (!$this->httpClient instanceof ClientInterface) {
            $this->httpClient = $this->buildHttpClient($this->httpClient);
        }

        if (!$this->uuidGenerator instanceof UuidGeneratorInterface) {
            $this->uuidGenerator = $this->buildUuidGenerator($this->uuidGenerator);
        }

        if (!$this->logger instanceof LoggerInterface) {
            $this->logger = new NullLogger();
        }
    }

    /**
     * Builds one of the built-in HTTP clients
     *
     * @param string $httpClient
     *
     * @return ClientInterface
     * @throws InvalidArgumentException
     */
    private function buildHttpClient($httpClient)
    {
        switch ($httpClient) {
            case 'guzzle':
                $this->verifyClassIsLoaded('Guzzle\Http\Client');

                return new GuzzleAdapter(new GuzzleClient('', $this->getGuzzleOptions()));

            case 'guzzle-http':
                $this->verifyClassIsLoaded('GuzzleHttp\Client');

                return new GuzzleHttpAdapter(new GuzzleHttpClient($this->getGuzzleOptions()));
        }

        throw new InvalidArgumentException('HTTP client is not valid. Built-in options are: guzzle and guzzle-http');
    }

    /**
     * Builds one of the built-in HTTP clients
     *
     * @param string $uuidGenerator
     *
     * @return UuidGeneratorInterface
     * @throws InvalidArgumentException
     */
    private function buildUuidGenerator($uuidGenerator)
    {
        switch ($uuidGenerator) {
            case 'ramsey':
                $this->verifyClassIsLoaded('Ramsey\Uuid\Uuid');

                return new RamseyUuidGenerator();

            case 'rhumsaa':
                $this->verifyClassIsLoaded('Rhumsaa\Uuid\Uuid');

                return new RhumsaaUuidGenerator();
        }

        throw new InvalidArgumentException('Uuid generator is not valid. Built-in options are: ramsey and rhumsaa');
    }

    /**
     * Verifies that the requested class is loaded before constructing it
     *
     * @param string $class
     */
    private function verifyClassIsLoaded($class)
    {
        if (!class_exists($class)) {
            throw new RuntimeException("The required class $class is not loaded");
        }
    }

    /**
     * Creates the options to use with guzzle HTTP clients
     *
     * @return array
     */
    private function getGuzzleOptions()
    {
        return [
            'timeout'         => $this->timeout,
            'connect_timeout' => $this->connectionTimeout,
            'exceptions'      => false,
        ];
    }
}
