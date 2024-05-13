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
 *
 * @internal
 *
 * @param ('GEOMETRY'|'CURVE'|'SOLID'|'SURFACE') $message the message to display
 */
final class NotInstantiableException extends \LogicException implements ExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        $advise = match ($message) {
            Parser::GEOMETRY => Parser::GEOMETRY_COLLECTION,
            Parser::CURVE => Parser::MULTI_CURVE,
            Parser::SOLID => Parser::POLYGON,
            Parser::SURFACE => Parser::MULTI_SURFACE,
            default => null,
        };

        $finalMessage = sprintf(
            'According the ISO 13249-3:2016 standard, the "%s" type is not instantiable.',
            $message
        );

        if (null !== $advise) {
            $finalMessage .= sprintf(
                ' Did you mean "%s"?',
                $advise
            );
        }

        parent::__construct($finalMessage, $code, $previous);
    }
}
