<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\RequestInterface;
use UglyGremlin\Layer\Exception\RequestException;
use UglyGremlin\Layer\Http\ClientInterface;

/**
 * Class GuzzleHttpAdapter
 *
 * @package UglyGremlin\Layer\Http\Client
 */
class GuzzleHttpAdapter implements ClientInterface
{
    /**
     * The Guzzle HTTP client
     *
     * @var Client
     */
    private $guzzle;

    /**
     * GuzzleHttpAdapter constructor.
     *
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(RequestInterface $request)
    {
        try {
            return $this->guzzle->request(
                $request->getMethod(),
                $request->getUri(),
                [
                    'headers' => $request->getHeaders(),
                    'json'    => $request->getBody(),
                ]
            );
        } catch (ClientException $exception) {
            // 40x errors
            return $exception->getResponse();
        } catch (ServerException $exception) {
            // 50x errors
            return $exception->getResponse();
        } catch (\Exception $exception) {
            throw new RequestException($request, $exception->getMessage(), 0, $exception);
        }
    }
}
