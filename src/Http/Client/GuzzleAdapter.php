<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Http\Client;

use Guzzle\Http\ClientInterface as GuzzleClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use UglyGremlin\Layer\Exception\RequestException;
use UglyGremlin\Layer\Http\ClientInterface;

/**
 * Class GuzzleAdapter
 *
 * @package UglyGremlin\Layer\Http\Client
 */
class GuzzleAdapter implements ClientInterface
{
    /**
     * @var GuzzleClient
     */
    private $guzzle;

    /**
     * GuzzleAdapter constructor.
     *
     * @param GuzzleClient $guzzle
     */
    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(RequestInterface $request)
    {
        return $this->convertResponse($this->executeWithGuzzle($request));
    }

    /**
     * Executes a HTTP request
     *
     * @param RequestInterface $request The request to execute
     *
     * @return GuzzleResponse
     */
    public function executeWithGuzzle(RequestInterface $request)
    {
        try {
            return $this->guzzle->createRequest(
                $request->getMethod(),
                (string) $request->getUri(),
                $request->getHeaders(),
                (string) $request->getBody()
            )->send();
        } catch (ClientErrorResponseException $exception) {
            // 40x errors
            return $exception->getResponse();
        } catch (ServerErrorResponseException $exception) {
            // 50x errors
            return $exception->getResponse();
        } catch (\Exception $exception) {
            throw new RequestException($request, $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Converts the guzzle response to the Psr interface
     *
     * @param GuzzleResponse $result
     *
     * @return Response
     */
    private function convertResponse(GuzzleResponse $result)
    {
        return new Response(
            $result->getStatusCode(),
            $result->getHeaders(),
            $result->getBody()->getSize() > 0 ? $result->getBody(true) : null
        );
    }
}
