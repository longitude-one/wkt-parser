<?php
/**
 * Copyright (C) 2015 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace LongitudeOne\Geo\WKT;

use Doctrine\Common\Lexer\AbstractLexer;

/**
 * Convert spatial value to tokens
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class Lexer extends AbstractLexer
{
    public const T_NONE              = 1;
    public const T_INTEGER           = 2;
    public const T_STRING            = 3;
    public const T_FLOAT             = 5;
    public const T_CLOSE_PARENTHESIS = 6;
    public const T_OPEN_PARENTHESIS  = 7;
    public const T_COMMA             = 8;
    public const T_DOT               = 10;
    public const T_EQUALS            = 11;
    public const T_MINUS             = 14;
    public const T_SEMICOLON         = 50;
    public const T_SRID              = 500;
    public const T_ZM                = 501;
    public const T_Z                 = 502;
    public const T_M                 = 503;

    // Geometry types > 600
    public const T_TYPE               = 600;
    public const T_POINT              = 601;
    public const T_LINESTRING         = 602;
    public const T_POLYGON            = 603;
    public const T_MULTIPOINT         = 604;
    public const T_MULTILINESTRING    = 605;
    public const T_MULTIPOLYGON       = 606;
    public const T_GEOMETRYCOLLECTION = 607;
    public const T_CIRCULARSTRING     = 608;
    public const T_COMPOUNDCURVE      = 609;
    public const T_CURVEPOLYGON       = 610;
    public const T_MULTICURVE         = 611;
    public const T_MULTISURFACE       = 612;
    public const T_CURVE              = 613;
    public const T_SURFACE            = 614;
    public const T_POLYHEDRALSURFACE  = 615;
    public const T_TIN                = 616;
    public const T_TRIANGLE           = 617;

    /**
     * @param string $input a query string
     */
    public function __construct($input = null)
    {
        if (null !== $input) {
            $this->setInput($input);
        }
    }

    /**
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->token->value;
    }

    /**
     * @param string $value
     *
     * @return int
     */
    protected function getType(&$value): int
    {
        if (is_numeric($value)) {
            $numeric = $value + 0;

            if (is_int($numeric)) {
                $value = $numeric;
                return self::T_INTEGER;
            }

            return self::T_FLOAT;
        }

        if (ctype_alpha($value)) {
            $name = __CLASS__ . '::T_' . strtoupper($value);

            if (defined($name)) {
                return constant($name);
            }

            return self::T_STRING;
        }

        return match ($value) {
            ','     => self::T_COMMA,
            '('     => self::T_OPEN_PARENTHESIS,
            ')'     => self::T_CLOSE_PARENTHESIS,
            '='     => self::T_EQUALS,
            ';'     => self::T_SEMICOLON,
            default => self::T_NONE,
        };
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
}
