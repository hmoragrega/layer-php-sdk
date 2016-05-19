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
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponse;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use UglyGremlin\Layer\Exception\RequestException;
use UglyGremlin\Layer\Http\ClientInterface;

/**
 * Class GuzzleHttpLegacyAdapter
 *
 * @package UglyGremlin\Layer\Http\Client
 */
class GuzzleHttpLegacyAdapter implements ClientInterface
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
            $guzzleResponse = $this->guzzle->send($this->guzzle->createRequest(
                $request->getMethod(),
                $request->getUri(), [
                    'headers' => $request->getHeaders(),
                    'json'    => $request->getBody(),
            ]));

            return $this->convertResponse($guzzleResponse);

        } catch (GuzzleRequestException $exception) {
            return $this->convertResponse($this->extractResponseFromException($request, $exception));

        } catch (\Exception $exception) {
            throw new RequestException($request, $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @param RequestInterface       $request
     * @param GuzzleRequestException $exception
     *
     * @return GuzzleResponse|null
     */
    private function extractResponseFromException(RequestInterface $request, GuzzleRequestException $exception)
    {
        if (!$exception->getResponse() instanceof GuzzleResponse) {
            throw new RequestException($request, $exception->getMessage(), 0, $exception);
        }

        return $exception->getResponse();
    }

    /**
     * @param GuzzleResponse $response
     *
     * @return Response
     */
    private function convertResponse(GuzzleResponse $response)
    {
        return new Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody()->getSize() > 0 ? $response->getBody()->getContents() : null
        );
    }
}
