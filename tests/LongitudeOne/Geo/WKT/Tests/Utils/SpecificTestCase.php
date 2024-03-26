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
    protected static function assertGeometryCollectionParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'GEOMETRYCOLLECTION', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    protected static function assertGeometryParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'GEOMETRY', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    protected static function assertLineStringParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'LINESTRING', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    protected static function assertMultiLineStringParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'MULTILINESTRING', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    protected static function assertMultiPointParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'MULTIPOINT', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    protected static function assertMultiPolygonParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'MULTIPOLYGON', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    protected static function assertPointParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'POINT', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    protected static function assertPolygonParsed(?int $expectedSrid, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        self::assertParsed($expectedSrid, 'POLYGON', $expectedCoordinates, $expectedDimension, $actual, $message);
    }

    private static function assertParsed(?int $expectedSrid, string $type, array $expectedCoordinates, ?string $expectedDimension, array $actual, string $message = ''): void
    {
        $expected['type'] = $type;
        $expected['value'] = $expectedCoordinates;
        $expected['srid'] = $expectedSrid;
        $expected['dimension'] = $expectedDimension;

        self::assertSame($expected, $actual, $message);
    }
}
