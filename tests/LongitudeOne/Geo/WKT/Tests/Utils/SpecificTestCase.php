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

namespace LongitudeOne\Geo\WKT\Tests\Utils;

use PHPUnit\Framework\TestCase;

class SpecificTestCase extends TestCase
{
    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int>[]                                                                                                                                                                                                                 $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertCircularStringParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'CIRCULARSTRING', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<array<int|float>>                                                                                                                                                                                                            $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertGeometryCollectionParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'GEOMETRYCOLLECTION', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<array<int|float>>                                                                                                                                                                                                            $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertGeometryParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'GEOMETRY', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int>[]                                                                                                                                                                                                                 $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertLineStringParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'LINESTRING', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int>[]                                                                                                                                                                                                                 $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertMultiLineStringParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'MULTILINESTRING', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int>[]                                                                                                                                                                                                                 $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertMultiPointParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'MULTIPOINT', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int>[]                                                                                                                                                                                                                 $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertMultiPolygonParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'MULTIPOLYGON', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int>                                                                                                                                                                                                                   $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertPointParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'POINT', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int>[]                                                                                                                                                                                                                 $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    protected static function assertPolygonParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'POLYGON', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    /**
     * Value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection.
     *
     * @param array<float|int|array<int|float>>                                                                                                                                                                                                  $expectedCoordinates
     *                                                                                                                                                                                                                                                                value is an array of             points           , linestring|multipoint, multilinestring|polygon, multipolygon            , geometry collection
     * @param array{type:string, value: array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]|array{'type':string, 'value':array<int|string>|array<int|string>[]|array<int|string>[][]|array<int|string>[][][]}[]} $actual
     */
    private static function assertParsed(?int $expectedSrid, string $type, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        $expected['type'] = $type;
        $expected['value'] = $expectedCoordinates;
        $expected['srid'] = $expectedSrid;
        $expected['dimension'] = $expectedDimension;

        self::assertSame($expected, $actual, $message);
    }
}
