<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Log;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Logger
 *
 * @package UglyGremlin\Layer\Log
 */
class Logger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PsrLogger constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logs the http message
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param \Exception        $exception
     */
    public function log(RequestInterface $request, ResponseInterface $response = null, \Exception $exception = null)
    {
        try {
            $this->logger->info("Layer API transaction", [
                'request'   => $request,
                'response'  => $response,
                'exception' => $exception,
            ]);
        } catch (\Exception $exception) {
            trigger_error($this->formatError($exception));
        }
    }

    /**
     * Formats the error based on the exception
     *
     * @param \Exception $exception
     *
     * @return string
     */
    private function formatError(\Exception $exception)
    {
        $error  = "Layer API logger found an error logging a transaction".PHP_EOL;
        $error .= "{$exception->getCode()}: {$exception->getMessage()}".PHP_EOL;
        $error .= "{$exception->getLine()} ({$exception->getLine()})".PHP_EOL;
        $error .= $exception->getTraceAsString();

        return $error;
    }
}
