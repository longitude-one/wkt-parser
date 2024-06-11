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

use LongitudeOne\Geo\WKT\Exception\NotExistentException;
use LongitudeOne\Geo\WKT\Exception\NotInstantiableException;
use LongitudeOne\Geo\WKT\Exception\NotYetImplementedException;
use LongitudeOne\Geo\WKT\Exception\UnexpectedValueException;

/**
 * Parse WKT/EWKT spatial object strings.
 *
 * @see ISO13249 Chapter 4.2
 * @see https://en.wikipedia.org/wiki/Well-known_text_representation_of_geometry
 */
class Parser
{
    public const BREP_SOLID = 'BREPSOLID';
    public const CIRCLE = 'CIRCLE';
    public const CIRCULAR_STRING = 'CIRCULARSTRING';
    public const CLOTHOID = 'CLOTHOID';
    public const COMPOUND_CURVE = 'COMPOUNDCURVE';
    public const COMPOUND_SURFACE = 'COMPOUNDSURFACE';
    public const CURVE = 'CURVE';
    public const CURVE_POLYGON = 'CURVEPOLYGON';
    public const ELLIPTICAL_CURVE = 'ELLIPTICALCURVE';
    public const GEODESIC_STRING = 'GEODESICSTRING';
    public const GEOMETRY = 'GEOMETRY';
    public const GEOMETRY_COLLECTION = 'GEOMETRYCOLLECTION';
    public const LINE_STRING = 'LINESTRING';
    public const MULTI_CURVE = 'MULTICURVE';
    public const MULTI_LINE_STRING = 'MULTILINESTRING';
    public const MULTI_POINT = 'MULTIPOINT';
    public const MULTI_POLYGON = 'MULTIPOLYGON';
    public const MULTI_SURFACE = 'MULTISURFACE';
    public const NURBS_CURVE = 'NURBSCURVE';
    public const POINT = 'POINT';
    public const POLYGON = 'POLYGON';
    public const POLYHEDRAL_SURFACE = 'POLYHDRLSURFACE';
    public const SOLID = 'SOLID';
    public const SPIRAL_CURVE = 'SPIRALCURVE';
    public const SURFACE = 'SURFACE';
    public const TIN = 'TIN';
    public const TRIANGLE = 'TRIANGLE';

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
     * return an array of                point            ,linestring|multipoint,multilinestring|polygon, multipolygon      , geometry collection.
     *
     * @return array{dimension: string|null, srid: int|null, type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]}
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
     * Match CIRCULARSTRING value.
     *
     * @return (int|string)[][]
     */
    protected function circularString(): array
    {
        return $this->pointList();
    }

    /**
     * Match a number and optional exponent.
     */
    protected function coordinate(): string|int
    {
        $this->match($this->lexer->isNextToken(Lexer::T_FLOAT) ? Lexer::T_FLOAT : Lexer::T_INTEGER);

        return $this->lexer->value();
    }

    /**
     * @return array<string|int>
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
     * return an array of                point            ,linestring|multipoint,multilinestring|polygon, multipolygon      , geometry collection.
     *
     * @return array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]}
     */
    protected function geometry(): array
    {
        try {
            $type = $this->type();
        } catch (UnexpectedValueException $e) {
            throw new NotExistentException((string) $this->lexer->lookahead?->value, $e->getCode(), $e);
        }

        if ($this->lexer->isNextTokenAny([Lexer::T_Z, Lexer::T_M, Lexer::T_ZM])) {
            /* @phpstan-ignore-next-line TOKEN is T_Z, T_M, or T_ZM so type is set */
            $this->match($this->lexer->lookahead->type);
            /* @phpstan-ignore-next-line TOKEN is T_Z, T_M, or T_ZM, so value is returning a string: 'Z', 'M' or 'ZM' */
            $this->dimension = $this->lexer->value();
        }

        $this->match(Lexer::T_OPEN_PARENTHESIS);

        $value = match ($type) {
            self::CIRCULAR_STRING => $this->circularString(),
            self::GEOMETRY_COLLECTION => $this->geometryCollection(),
            self::LINE_STRING => $this->lineString(),
            self::MULTI_LINE_STRING => $this->multiLineString(),
            self::MULTI_POINT => $this->multiPoint(),
            self::MULTI_POLYGON => $this->multiPolygon(),
            self::POINT => $this->point(),
            self::POLYGON => $this->polygon(),
            self::TRIANGLE => $this->triangle(), // PostGis ✅, MySQL ❌

            // ❌ Not implemented types in longitude-one/geo-parser
            self::BREP_SOLID, // PostGis ❌, MySQL ❌
            self::CIRCLE, // PostGis ❌, MySQL ❌
            self::CLOTHOID, // PostGis ❌, MySQL ❌
            self::COMPOUND_CURVE, // PostGis ❌, MySQL ❌
            self::COMPOUND_SURFACE, // PostGis ❌, MySQL ❌
            self::CURVE_POLYGON, // PostGis ✅, MySQL ❌
            self::ELLIPTICAL_CURVE, // PostGis ❌, MySQL ❌
            self::GEODESIC_STRING, // PostGis ❌, MySQL ❌
            self::MULTI_CURVE, // PostGis ✅, MySQL ✅
            self::MULTI_SURFACE, // PostGis ✅, MySQL ✅
            self::NURBS_CURVE, // PostGis ❌, MySQL ❌
            self::SPIRAL_CURVE, // PostGis ❌, MySQL ❌
            self::POLYHEDRAL_SURFACE, // PostGis ✅, MySQL ❌
            self::TIN, => throw new NotYetImplementedException($type), // PostGis ✅, MySQL ❌

            // @see ISO13249-3 Chapter 4.2 §2 page 11
            // Curve, geometry, solid and surface aren't instantiable!
            self::CURVE,
            self::GEOMETRY,
            self::SOLID,
            self::SURFACE => throw new NotInstantiableException($type),

            // This should never happen, because Lexer will throw an UnexpectedValueException
            default => throw new NotExistentException($type),
        };

        $this->match(Lexer::T_CLOSE_PARENTHESIS);

        return [
            'type' => $type,
            'value' => $value,
        ];
    }

    /**
     * Match GEOMETRYCOLLECTION value.
     *
     * no recursive here, only one level of geometry collection.
     *
     * @return array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]
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
     * @return array<int|string>[]
     */
    protected function lineString(): array
    {
        return $this->pointList();
    }

    /**
     * Match token at current position in input.
     *
     * @throw UnexpectedValueException
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
     * @return array<int|string>[][]
     */
    protected function multiLineString(): array
    {
        return $this->pointLists();
    }

    /**
     * Match MULTIPOINT value.
     *
     * @return array<int|string>[]
     */
    protected function multiPoint(): array
    {
        return $this->pointList();
    }

    /**
     * Match MULTIPOLYGON value.
     *
     * @return array<int|string>[][][]
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
     * @return array<int|string>
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
     * @return (int|string)[][]
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
     * @return array<int|string>[][]
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
     * @return array<int|string>[][]
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

        /** @var int $srid because we just match a Lexer::T_INTEGER */
        $srid = $this->lexer->value();

        $this->match(Lexer::T_SEMICOLON);

        return $srid;
    }

    /**
     * Match TRIANGLE value.
     *
     * @return (int|string)[][]
     */
    protected function triangle(): array
    {
        // TRIANGLE ((0 0, 0 9, 9 0, 0 0))
        $this->match(Lexer::T_OPEN_PARENTHESIS);
        $pointList = $this->pointList();
        $this->match(Lexer::T_CLOSE_PARENTHESIS);

        if (4 !== count($pointList)) {
            throw new UnexpectedValueException(sprintf('According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided %d.', count($pointList)));
        }

        if ($pointList[0] !== $pointList[3]) {
            throw new UnexpectedValueException(sprintf('According to the ISO-13249 specification, a triangle is a closed ring with fourth points. Your first point is "%s", the fourth is "%s"', implode(' ', $pointList[0]), implode(' ', $pointList[3])));
        }

        return $pointList;
    }

    /**
     * Match a spatial data type.
     */
    protected function type(): string
    {
        $this->match(Lexer::T_TYPE);

        return (string) $this->lexer->value();
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
