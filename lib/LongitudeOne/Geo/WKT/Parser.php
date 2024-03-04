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

use LongitudeOne\Geo\WKT\Exception\UnexpectedValueException;

/**
 * Parse WKT/EWKT spatial object strings
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class Parser
{
    private ?string $input = null;
    private ?string $dimension = null;
    private Lexer $lexer;

    /**
     * @param string | null $input
     */
    public function __construct(?string $input = null)
    {
        $this->lexer = new Lexer();

        if (null !== $input) {
            $this->input = $input;
        }
    }

    /**
     * @param string | null $input
     *
     * @return array
     */
    public function parse(?string $input = null): array
    {
        if (null !== $input) {
            $this->input = $input;
        }

        $this->lexer->setInput($this->input);
        $this->lexer->moveNext();

        $srid = null;
        $this->dimension = null;

        if ($this->lexer->isNextToken(Lexer::T_SRID)) {
            $srid = $this->srid();
        }

        $geometry = $this->geometry();
        $geometry['srid'] = $srid;
        $geometry['dimension'] = '' === $this->dimension ? null : $this->dimension;

        return $geometry;
    }

    /**
     * Match SRID in EWKT object.
     *
     * @return int
     */
    protected function srid(): int
    {
        $this->match(Lexer::T_SRID);
        $this->match(Lexer::T_EQUALS);
        $this->match(Lexer::T_INTEGER);

        $srid = $this->lexer->value();

        $this->match(Lexer::T_SEMICOLON);

        return $srid;
    }

    /**
     * Match token at current position in input.
     *
     * @param $token
     */
    protected function match($token): void
    {
        if ($this->lexer->lookahead !== null) {
            $lookaheadType = $this->lexer->lookahead->type;
        }

        if (!isset($lookaheadType) || ($lookaheadType !== $token && ($token !== Lexer::T_TYPE || $lookaheadType <= Lexer::T_TYPE))) {
            throw $this->syntaxError($this->lexer->getLiteral($token));
        }

        $this->lexer->moveNext();
    }

    /**
     * Create exception with a descriptive error message.
     *
     * @param string $expected
     *
     * @return UnexpectedValueException
     */
    private function syntaxError(string $expected): UnexpectedValueException
    {
        $expected = sprintf('Expected %s, got', $expected);
        $token = $this->lexer->lookahead;
        $found = null === $this->lexer->lookahead ? 'end of string.' : sprintf('"%s"', $token->value);
        $message = sprintf(
            '[Syntax Error] line 0, col %d: Error: %s %s in value "%s"',
            isset($token->position) ? $token->position : '-1',
            $expected,
            $found,
            $this->input,
        );

        return new UnexpectedValueException($message);
    }

    /**
     * Match spatial geometry object
     *
     * @return array
     */
    protected function geometry(): array
    {
        $type = $this->type();

        if ($this->lexer->isNextTokenAny([Lexer::T_Z, Lexer::T_M, Lexer::T_ZM])) {
            $this->match($this->lexer->lookahead->type);

            $this->dimension = $this->lexer->value();
        }

        $this->match(Lexer::T_OPEN_PARENTHESIS);

        $value = $this->$type();

        $this->match(Lexer::T_CLOSE_PARENTHESIS);

        return [
            'type' => $type,
            'value' => $value,
        ];
    }

    /**
     * Match spatial data type
     *
     * @return string
     */
    protected function type(): string
    {
        $this->match(Lexer::T_TYPE);

        return $this->lexer->value();
    }

    /**
     * Match LINESTRING value
     *
     * @return array[]
     */
    protected function lineString(): array
    {
        return $this->pointList();
    }

    /**
     * Match a list of coordinates
     *
     * @return array[]
     */
    protected function pointList(): array
    {
        $points = [$this->point()];

        while ($this->lexer->isNextToken(Lexer::T_COMMA)) {
            $this->match(Lexer::T_COMMA);

            $points[] = $this->point();
        }

        return $points;
    }

    /**
     * Match a coordinate pair
     *
     * @return array
     */
    protected function point(): array
    {
        if (null !== $this->dimension) {
            return $this->coordinates(2 + strlen($this->dimension));
        }

        $values = $this->coordinates(2);

        for ($i = 3; $i <= 4 && $this->lexer->isNextTokenAny([Lexer::T_FLOAT, Lexer::T_INTEGER]); ++$i) {
            $values[] = $this->coordinate();
        }

        switch (count($values)) {
            case 2:
                $this->dimension = '';
                break;
            case 3:
                $this->dimension = 'Z';
                break;
            case 4:
                $this->dimension = 'ZM';
                break;
        }

        return $values;
    }

    /**
     * @param int $count
     *
     * @return array
     */
    protected function coordinates(int $count): array
    {
        $values = [];

        for ($i = 1; $i <= $count; ++$i) {
            $values[] = $this->coordinate();
        }

        return $values;
    }

    /**
     * Match a number and optional exponent
     *
     * @return float | int
     */
    protected function coordinate(): float | int
    {
        $this->match(($this->lexer->isNextToken(Lexer::T_FLOAT) ? Lexer::T_FLOAT : Lexer::T_INTEGER));

        return $this->lexer->value();
    }

    /**
     * Match MULTIPOLYGON value
     *
     * @return array[]
     */
    protected function multiPolygon(): array
    {
        $this->match(Lexer::T_OPEN_PARENTHESIS);

        $polygons = [$this->polygon()];

        $this->match(Lexer::T_CLOSE_PARENTHESIS);

        while ($this->lexer->isNextToken(Lexer::T_COMMA)) {
            $this->match(Lexer::T_COMMA);
            $this->match(Lexer::T_OPEN_PARENTHESIS);

            $polygons[] = $this->polygon();

            $this->match(Lexer::T_CLOSE_PARENTHESIS);
        }

        return $polygons;
    }

    /**
     * Match POLYGON value
     *
     * @return array[]
     */
    protected function polygon(): array
    {
        return $this->pointLists();
    }

    /**
     * Match nested lists of coordinates
     *
     * @return array[]
     */
    protected function pointLists(): array
    {
        $this->match(Lexer::T_OPEN_PARENTHESIS);

        $pointLists = [$this->pointList()];

        $this->match(Lexer::T_CLOSE_PARENTHESIS);

        while ($this->lexer->isNextToken(Lexer::T_COMMA)) {
            $this->match(Lexer::T_COMMA);
            $this->match(Lexer::T_OPEN_PARENTHESIS);

            $pointLists[] = $this->pointList();

            $this->match(Lexer::T_CLOSE_PARENTHESIS);
        }

        return $pointLists;
    }

    /**
     * Match MULTIPOINT value
     *
     * @return array[]
     */
    protected function multiPoint(): array
    {
        return $this->pointList();
    }

    /**
     * Match MULTILINESTRING value
     *
     * @return array[]
     */
    protected function multiLineString(): array
    {
        return $this->pointLists();
    }

    /**
     * Match GEOMETRYCOLLECTION value
     *
     * @return array[]
     */
    protected function geometryCollection(): array
    {
        $collection = [$this->geometry()];

        while ($this->lexer->isNextToken(Lexer::T_COMMA)) {
            $this->match(Lexer::T_COMMA);

            $collection[] = $this->geometry();
        }

        return $collection;
    }
}
