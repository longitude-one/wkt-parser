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

/**
 * Not yet implemented exception.
 */
final class NotExistentException extends \LogicException implements ExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        $finalMessage = sprintf(
            'According the ISO 13249-3:2016 standard, the "%s" type does not exist.',
            $message
        );

        parent::__construct($finalMessage, $code, $previous);
    }
}
