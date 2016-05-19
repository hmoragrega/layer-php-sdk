<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use UglyGremlin\Layer\Api\RequestFactory;
use UglyGremlin\Layer\Api\ResponseChecker;
use UglyGremlin\Layer\Exception\InvalidArgumentException;
use UglyGremlin\Layer\Exception\RuntimeException;
use UglyGremlin\Layer\Http\Client\GuzzleAdapterFactory;
use UglyGremlin\Layer\Http\ClientInterface;
use UglyGremlin\Layer\Log\Logger;
use UglyGremlin\Layer\Uuid\Generator\RamseyUuidGenerator;
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
    private $baseUrl;

    /**
     * Request execution timeout in seconds
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * Request execution timeout in seconds
     *
     * @var UuidGeneratorInterface
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
     * @param ClientInterface $httpClient The HTTP client
     *
     * @return $this
     */
    public function withHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Sets the request connection timeout in seconds
     *
     * @param UuidGeneratorInterface $uuidGenerator The UUID generator
     *
     * @return $this
     */
    public function withUuidGenerator(UuidGeneratorInterface $uuidGenerator)
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
            $this->httpClient = GuzzleAdapterFactory::build($this->getGuzzleOptions());
        }

        if (!$this->uuidGenerator instanceof UuidGeneratorInterface) {
            $this->uuidGenerator = new RamseyUuidGenerator();
        }

        if (!$this->logger instanceof LoggerInterface) {
            $this->logger = new NullLogger();
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
