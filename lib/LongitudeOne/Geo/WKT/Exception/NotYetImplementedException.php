<?php

/**
 * This file is part of the LongitudeOne WKT-Parser project.
 *
 * PHP 8.1 - 8.5
 *
 * Copyright LongitudeOne - Alexandre Tranchant - Derek J. Lambert.
 * Copyright 2024-2026.
 *
 */

namespace LongitudeOne\Geo\WKT\Exception;

use LongitudeOne\Geo\WKT\Parser;

/**
 * Not yet implemented exception.
 */
final class NotYetImplementedException extends \LogicException implements ExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        $finalMessage = sprintf(
            'The %s is not yet able to parse "%s".',
            Parser::class,
            $message
        );

        parent::__construct($finalMessage, $code, $previous);
    }
}
