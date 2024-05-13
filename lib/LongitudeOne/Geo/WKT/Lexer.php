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

namespace LongitudeOne\Geo\WKT;

use Doctrine\Common\Lexer\AbstractLexer;

/**
 * Convert spatial value to tokens.
 *
 * @extends AbstractLexer<int, int|string>
 */
class Lexer extends AbstractLexer
{
    public const T_CIRCULARSTRING = 608;
    public const T_CLOSE_PARENTHESIS = 6;
    public const T_COMMA = 8;
    public const T_COMPOUNDCURVE = 609;
    public const T_CURVE = 613;
    public const T_CURVEPOLYGON = 610;
    public const T_DOT = 10;
    public const T_EQUALS = 11;
    public const T_FLOAT = 5;
    public const T_GEOMETRY = 690;
    public const T_GEOMETRYCOLLECTION = 607;
    public const T_INTEGER = 2;
    public const T_LINESTRING = 602;
    public const T_M = 503;
    public const T_MINUS = 14;
    public const T_MULTICURVE = 611;
    public const T_MULTILINESTRING = 605;
    public const T_MULTIPOINT = 604;
    public const T_MULTIPOLYGON = 606;
    public const T_MULTISURFACE = 612;
    public const T_NONE = 1;
    public const T_OPEN_PARENTHESIS = 7;
    public const T_POINT = 601;
    public const T_POLYGON = 603;
    public const T_POLYHEDRALSURFACE = 615;
    public const T_SEMICOLON = 50;
    public const T_SOLID = 699;
    public const T_SRID = 500;
    public const T_STRING = 3;
    public const T_SURFACE = 614;
    public const T_TIN = 616;
    public const T_TRIANGLE = 617;
    public const T_TYPE = 600;
    public const T_Z = 502;
    public const T_ZM = 501;

    public function __construct(?string $input = null)
    {
        if (null !== $input) {
            $this->setInput((string) $input);
        }
    }

    public function value(): int|string
    {
        if (is_int($this->token?->value)) {
            return (int) $this->token->value;
        }

        if (is_numeric($this->token?->value)) {
            return (string) ($this->token->value + 0);
        }

        return $this->token?->value ?? '';
    }

    /**
     * @return string[]
     */
    protected function getCatchablePatterns(): array
    {
        return [
            '',
            'zm|[a-z]+[a-ln-y]',
            '[+-]?[0-9]+(?:[\.][0-9]+)?(?:e[+-]?[0-9]+)?',
        ];
    }

    /**
     * @return string[]
     */
    protected function getNonCatchablePatterns(): array
    {
        return ['\s+'];
    }

    /**
     * @param string $value
     */
    protected function getType(&$value): int
    {
        if (is_numeric($value)) {
            $value += 0;

            if (is_int($value)) {
                return self::T_INTEGER;
            }

            $value = (string) $value;

            return self::T_FLOAT;
        }

        if (preg_match('/^[a-zA-Z]+$/', $value)) {
            $name = __CLASS__.'::T_'.strtoupper($value);

            if (defined($name) && is_int(constant($name))) {
                return constant($name);
            }

            return self::T_STRING;
        }

        return match ($value) {
            ',' => self::T_COMMA,
            '(' => self::T_OPEN_PARENTHESIS,
            ')' => self::T_CLOSE_PARENTHESIS,
            '=' => self::T_EQUALS,
            ';' => self::T_SEMICOLON,
            default => self::T_NONE,
        };
    }
}
