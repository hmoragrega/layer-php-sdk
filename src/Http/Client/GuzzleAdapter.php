<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Http\Client;

use Guzzle\Http\ClientInterface as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;
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
        try {
            $response = $this->guzzle->createRequest(
                $request->getMethod(),
                (string) $request->getUri(),
                $request->getHeaders(),
                (string) $request->getBody()
            )->send();

            return $this->convertResponse($response);
            
        } catch (BadResponseException $exception) {
            return $this->extractResponseFromException($request, $exception->getResponse());

        } catch (\Exception $exception) {
            throw new RequestException($request, $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @param RequestInterface     $request
     * @param BadResponseException $exception
     *
     * @return GuzzleResponse
     */
    private function extractResponseFromException(RequestInterface $request, BadResponseException $exception)
    {
        if (!$exception->getResponse() instanceof GuzzleResponse) {
            throw new RequestException($request, $exception->getMessage(), 0, $exception);
        }

        return $this->convertResponse($exception->getResponse());
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
            $result->getHeaders()->toArray(),
            $result->getBody()->getSize() > 0 ? $result->getBody(true) : null
        );
    }
}
