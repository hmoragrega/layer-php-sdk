<?php

/**
 * Layer PHP SDK
 *
 * @author  Hilari Moragrega <hilari@hilarimoragrega.com>
 * @license Apache License, Version 2.0
 */

namespace UglyGremlin\Layer\Log;

use GuzzleHttp\MessageFormatter;
use Monolog\Formatter\LineFormatter;
use Psr\Http\Message\RequestInterface;

/**
 * Class GuzzleHttpMessageFormatter
 *
 * @package UglyGremlin\Layer\Api
 */
class GuzzleHttpMessageFormatter extends LineFormatter
{
    /**
     * @var MessageFormatter
     */
    private $formatter;

    /**
     * GuzzleHttpMessageFormatter constructor.
     *
     * @param null|string $template
     * @param null        $format
     * @param null        $dateFormat
     * @param null        $allowInlineLineBreaks
     * @param null        $ignoreEmptyContextAndExtra
     */
    public function __construct(
        $template = MessageFormatter::CLF,
        $format = null,
        $dateFormat = null,
        $allowInlineLineBreaks = null,
        $ignoreEmptyContextAndExtra = null
    ) {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
        $this->formatter = new MessageFormatter($template);
    }

    /**
     * Formats a log record.
     *
     * @param array $record A record to format
     *
     * @return string The formatted record
     */
    public function format(array $record)
    {
        if (isset($record['context']['request']) && $record['context']['request'] instanceof RequestInterface) {
            $record['context']['transaction'] = $this->formatter->format(
                $record['context']['request'],
                $record['context']['response']
            );
        }

        return parent::format($record);
    }
}
