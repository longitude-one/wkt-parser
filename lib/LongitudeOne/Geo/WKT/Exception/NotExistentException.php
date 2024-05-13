<?php

/**
 * This file is part of the LongitudeOne WKT-Parser project.
 *
 * PHP 8.1 | 8.2 | 8.3
 *
 * Copyright LongitudeOne - Alexandre Tranchant - Derek J. Lambert.
 * Copyright 2024.
 *
 */

namespace LongitudeOne\Geo\WKT\Exception;

use LongitudeOne\Geo\WKT\Parser;

/**
 * Not yet implemented exception.
 */
final class NotExistentException extends \LogicException implements ExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        $finalMessage = sprintf(
            '%s: The "%s" type does not exist.',
            Parser::class,
            $message
        );

        parent::__construct($finalMessage, $code, $previous);
    }
}
