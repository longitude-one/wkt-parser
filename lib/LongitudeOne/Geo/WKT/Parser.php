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

use LongitudeOne\Geo\WKT\Exception\UnexpectedValueException;

/**
 * Parse WKT/EWKT spatial object strings.
 */
class Parser
{
    private ?string $dimension = null;
    private ?string $input = null;
    private Lexer $lexer;

    public function __construct(?string $input = null)
    {
        $this->lexer = new Lexer();

        if (null !== $input) {
            $this->input = $input;
        }
    }

    /**
     * Parse WKT/EWKT string.
     *
     * geometryCollection
     *
     * @return array{type: string, value: array<array<float|int>|float|int>, srid: int|null, dimension: string|null}
     */
    public function parse(?string $input = null): array
    {
        if (null !== $input) {
            $this->input = $input;
        }

        if (null === $this->input) {
            throw new UnexpectedValueException('No value provided');
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
        $geometry['dimension'] = empty($this->dimension) ? null : $this->dimension;

        return $geometry;
    }

    /**
     * Match a number and optional exponent.
     */
    protected function coordinate(): float|int
    {
        $this->match($this->lexer->isNextToken(Lexer::T_FLOAT) ? Lexer::T_FLOAT : Lexer::T_INTEGER);

        return $this->lexer->value();
    }

    /**
     * @return array<float|int>
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
     * Match a spatial geometry object.
     * return an array of point, linestring, multilinestring|polygon, multipolygon, etc.
     *
     * @return array{'type':string, 'value':array<int|float>|array<int|float>[]|array<int|float>[][]|array<int|float>[][][]}
     */
    protected function geometry(): array
    {
        $type = $this->type();

        if ($this->lexer->isNextTokenAny([Lexer::T_Z, Lexer::T_M, Lexer::T_ZM])) {
            $this->match($this->lexer->lookahead->type);
            // TOKEN is T_Z, T_M, or T_ZM, so dimension is a string 'Z', 'M' or 'ZM'
            $this->dimension = $this->lexer->value();
        }

        $this->match(Lexer::T_OPEN_PARENTHESIS);

        // FIXME SECURITY: replace with a match function and throw an InvalidArgumentException
        $value = $this->$type();

        $this->match(Lexer::T_CLOSE_PARENTHESIS);

        return [
            'type' => $type,
            'value' => $value,
        ];
    }

    /**
     * Match GEOMETRYCOLLECTION value.
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

    /**
     * Match LINESTRING value.
     *
     * @return array<int|float>[]
     */
    protected function lineString(): array
    {
        return $this->pointList();
    }

    /**
     * Match token at current position in input.
     */
    protected function match(int $token): void
    {
        if (null !== $this->lexer->lookahead) {
            $lookaheadType = $this->lexer->lookahead->type;
        }

        if (!isset($lookaheadType) || ($lookaheadType !== $token && (Lexer::T_TYPE !== $token || $lookaheadType <= Lexer::T_TYPE))) {
            throw $this->syntaxError((string) $this->lexer->getLiteral($token));
        }

        $this->lexer->moveNext();
    }

    /**
     * Match MULTILINESTRING value.
     *
     * @return array<int|float>[][]
     */
    protected function multiLineString(): array
    {
        return $this->pointLists();
    }

    /**
     * Match MULTIPOINT value.
     *
     * @return array<int|float>[]
     */
    protected function multiPoint(): array
    {
        return $this->pointList();
    }

    /**
     * Match MULTIPOLYGON value.
     *
     * @return array<int|float>[][][]
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
     * Match a coordinate pair.
     *
     * @return array<int|float>
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
     * Match a list of coordinates.
     *
     * @return array<int|float>[]
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
     * Match nested lists of coordinates.
     *
     * @return array<int|float>[][]
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
     * Match POLYGON value.
     *
     * @return array<int|float>[][]
     */
    protected function polygon(): array
    {
        return $this->pointLists();
    }

    /**
     * Match SRID in EWKT object.
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
     * Match a spatial data type.
     */
    protected function type(): string
    {
        $this->match(Lexer::T_TYPE);

        return $this->lexer->value();
    }

    /**
     * Create exception with a descriptive error message.
     */
    private function syntaxError(string $expected): UnexpectedValueException
    {
        $expected = sprintf('Expected %s, got', $expected);
        $token = $this->lexer->lookahead;
        $found = null === $this->lexer->lookahead ? 'end of string.' : sprintf('"%s"', $token?->value);
        $message = sprintf(
            '[Syntax Error] line 0, col %d: Error: %s %s in value "%s"',
            $token->position ?? -1,
            $expected,
            $found,
            $this->input,
        );

        return new UnexpectedValueException($message);
    }
}
