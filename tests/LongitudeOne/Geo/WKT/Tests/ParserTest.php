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

namespace LongitudeOne\Geo\WKT\Tests;

use LongitudeOne\Geo\WKT\Exception\UnexpectedValueException;
use LongitudeOne\Geo\WKT\Parser;
use LongitudeOne\Geo\WKT\Tests\Utils\SpecificTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Basic parser tests.
 */
class ParserTest extends SpecificTestCase
{
    /**
     * @return \Generator<string, ?int, array<int|float>[], ?string>
     */
    public static function geometryCollectionProvider(): \Generator
    {
        yield 'testGeometryCollection' => ['GEOMETRYCOLLECTION(POINT(34.23 -87), LINESTRING(34.23 -87, 45.3 -92))', null, [['type' => 'POINT', 'value' => [34.23, -87]], ['type' => 'LINESTRING', 'value' => [[34.23, -87], [45.3, -92]]]], null];
        yield 'testGeometryCollectionWithSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT(34.23 -87), LINESTRING(34.23 -87, 45.3 -92))', 4326, [['type' => 'POINT', 'value' => [34.23, -87]], ['type' => 'LINESTRING', 'value' => [[34.23, -87], [45.3, -92]]]], null];
        yield 'testGeometryCollectionWithZ' => ['GEOMETRYCOLLECTION(POINT Z(34.23 -87 10), LINESTRING Z(34.23 -87 10, 45.3 -92 10))', null, [['type' => 'POINT', 'value' => [34.23, -87, 10]], ['type' => 'LINESTRING', 'value' => [[34.23, -87, 10], [45.3, -92, 10]]]], 'Z'];
        yield 'testGeometryCollectionWithZAndSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT Z(34.23 -87 10), LINESTRING Z(34.23 -87 10, 45.3 -92 10))', 4326, [['type' => 'POINT', 'value' => [34.23, -87, 10]], ['type' => 'LINESTRING', 'value' => [[34.23, -87, 10], [45.3, -92, 10]]]], 'Z'];
        yield 'testGeometryCollectionWithM' => ['GEOMETRYCOLLECTION(POINT M(34.23 -87 10), LINESTRING M(34.23 -87 10, 45.3 -92 10))', null, [['type' => 'POINT', 'value' => [34.23, -87, 10]], ['type' => 'LINESTRING', 'value' => [[34.23, -87, 10], [45.3, -92, 10]]]], 'M'];
        yield 'testGeometryCollectionWithMAndSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT M(34.23 -87 10), LINESTRING M(34.23 -87 10, 45.3 -92 10))', 4326, [['type' => 'POINT', 'value' => [34.23, -87, 10]], ['type' => 'LINESTRING', 'value' => [[34.23, -87, 10], [45.3, -92, 10]]]], 'M'];
        yield 'testGeometryCollectionWithZM' => ['GEOMETRYCOLLECTION(POINT ZM(34.23 -87 10 20), LINESTRING ZM(34.23 -87 10 20, 45.3 -92 10 20))', null, [['type' => 'POINT', 'value' => [34.23, -87, 10, 20]], ['type' => 'LINESTRING', 'value' => [[34.23, -87, 10, 20], [45.3, -92, 10, 20]]]], 'ZM'];
        yield 'testGeometryCollectionWithZMAndSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT ZM(34.23 -87 10 20), LINESTRING ZM(34.23 -87 10 20, 45.3 -92 10 20))', 4326, [['type' => 'POINT', 'value' => [34.23, -87, 10, 20]], ['type' => 'LINESTRING', 'value' => [[34.23, -87, 10, 20], [45.3, -92, 10, 20]]]], 'ZM'];
    }

    /**
     * @return \Generator<string, ?int, array<int|float>[], ?string>
     */
    public static function lineStringProvider(): \Generator
    {
        yield 'testParsingLineStringValue' => ['LINESTRING(34.23 -87, 45.3 -92)', null, [[34.23, -87], [45.3, -92]], null];
        yield 'testParsingLineStringZValue' => ['LINESTRING(34.23 -87 10, 45.3 -92 10)', null, [[34.23, -87, 10], [45.3, -92, 10]], 'Z'];
        yield 'testParsingLineStringMValue' => ['LINESTRINGM(34.23 -87 10, 45.3 -92 10)', null, [[34.23, -87, 10], [45.3, -92, 10]], 'M'];
        yield 'testParsingLineStringZMValue' => ['LINESTRINGZM(34.23 -87 10 20, 45.3 -92 10 20)', null, [[34.23, -87, 10, 20], [45.3, -92, 10, 20]], 'ZM'];
        yield 'testParsingLineStringValueWithSrid' => ['SRID=4326;LINESTRING(34.23 -87, 45.3 -92)', 4326, [[34.23, -87], [45.3, -92]], null];
        yield 'testParsingLineStringZValueWithSrid' => ['SRID=4326;LINESTRING(34.23 -87 10, 45.3 -92 10)', 4326, [[34.23, -87, 10], [45.3, -92, 10]], 'Z'];
        yield 'testParsingLineStringMValueWithSrid' => ['SRID=4326;LINESTRINGM(34.23 -87 10, 45.3 -92 10)', 4326, [[34.23, -87, 10], [45.3, -92, 10]], 'M'];
        yield 'testParsingLineStringZMValueWithSrid' => ['SRID=4326;LINESTRINGZM(34.23 -87 10 20, 45.3 -92 10 20)', 4326, [[34.23, -87, 10, 20], [45.3, -92, 10, 20]], 'ZM'];
    }

    /**
     * @return \Generator<string, ?int, array<int|float>[], ?string>
     */
    public static function multiLineStringProvider(): \Generator
    {
        yield 'testParsingMultiLineStringValue' => ['MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))', null, [[[0, 0], [10, 0], [10, 10], [0, 10]], [[5, 5], [7, 5], [7, 7], [5, 7]]], null];
        yield 'testParsingMultiLineStringZValue' => ['MULTILINESTRINGZ((0 0 0,10 0 0,10 10 0,0 10 0),(5 5 1,7 5 1,7 7 1,5 7 1))', null, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1]]], 'Z'];
        yield 'testParsingMultiLineStringMValue' => ['MULTILINESTRINGM((0 0 0,10 0 0,10 10 0,0 10 0),(5 5 1,7 5 1,7 7 1,5 7 1))', null, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1]]], 'M'];
        yield 'testParsingMultiLineStringZMValue' => ['MULTILINESTRINGZM((0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1),(5 5 1 2,7 5 1 2,7 7 1 2,5 7 1 2))', null, [[[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1]], [[5, 5, 1, 2], [7, 5, 1, 2], [7, 7, 1, 2], [5, 7, 1, 2]]], 'ZM'];
        yield 'testParsingMultiLineStringValueWithSrid' => ['SRID=4326;MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))', 4326, [[[0, 0], [10, 0], [10, 10], [0, 10]], [[5, 5], [7, 5], [7, 7], [5, 7]]], null];
        yield 'testParsingMultiLineStringZValueWithSrid' => ['SRID=4326;MULTILINESTRINGZ((0 0 0,10 0 0,10 10 0,0 10 0),(5 5 1,7 5 1,7 7 1,5 7 1))', 4326, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1]]], 'Z'];
        yield 'testParsingMultiLineStringMValueWithSrid' => ['SRID=4326;MULTILINESTRINGM((0 0 0,10 0 0,10 10 0,0 10 0),(5 5 1,7 5 1,7 7 1,5 7 1))', 4326, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1]]], 'M'];
        yield 'testParsingMultiLineStringZMValueWithSrid' => ['SRID=4326;MULTILINESTRINGZM((0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1),(5 5 1 2,7 5 1 2,7 7 1 2,5 7 1 2))', 4326, [[[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1]], [[5, 5, 1, 2], [7, 5, 1, 2], [7, 7, 1, 2], [5, 7, 1, 2]]], 'ZM'];
    }

    /**
     * @return \Generator<string, ?int, array<int|float>[], ?string>
     */
    public static function multiPointProvider(): \Generator
    {
        yield 'testParsingMultiPointValue' => ['MULTIPOINT(0 0,10 0,10 10,0 10)', null, [[0, 0], [10, 0], [10, 10], [0, 10]], null];
        yield 'testParsingMultiPointZValue' => ['MULTIPOINTZ(0 0 0,10 0 0,10 10 0,0 10 0)', null, [[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], 'Z'];
        yield 'testParsingMultiPointMValue' => ['MULTIPOINTM(0 0 0,10 0 0,10 10 0,0 10 0)', null, [[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], 'M'];
        yield 'testParsingMultiPointZMValue' => ['MULTIPOINTZM(0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1)', null, [[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1]], 'ZM'];
        yield 'testParsingMultiPointValueWithSrid' => ['SRID=4326;MULTIPOINT(0 0,10 0,10 10,0 10)', 4326, [[0, 0], [10, 0], [10, 10], [0, 10]], null];
        yield 'testParsingMultiPointZValueWithSrid' => ['SRID=4326;MULTIPOINTZ(0 0 0,10 0 0,10 10 0,0 10 0)', 4326, [[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], 'Z'];
        yield 'testParsingMultiPointMValueWithSrid' => ['SRID=4326;MULTIPOINTM(0 0 0,10 0 0,10 10 0,0 10 0)', 4326, [[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0]], 'M'];
        yield 'testParsingMultiPointZMValueWithSrid' => ['SRID=4326;MULTIPOINTZM(0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1)', 4326, [[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1]], 'ZM'];
    }

    /**
     * @return \Generator<string, ?int, array<int|float>[], ?string>
     */
    public static function multiPolygonProvider(): \Generator
    {
        yield 'testParsingMultiPolygonValue' => ['MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))', null, [[[[0, 0], [10, 0], [10, 10], [0, 10], [0, 0]], [[5, 5], [7, 5], [7, 7], [5, 7], [5, 5]]], [[[1, 1], [3, 1], [3, 3], [1, 3], [1, 1]]]], null];
        yield 'testParsingMultiPolygonZValue' => ['MULTIPOLYGON(((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0),(5 5 1,7 5 1,7 7 1,5 7 1,5 5 1)),((1 1 0, 3 1 0, 3 3 0, 1 3 0, 1 1 0)))', null, [[[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1], [5, 5, 1]]], [[[1, 1, 0], [3, 1, 0], [3, 3, 0], [1, 3, 0], [1, 1, 0]]]], 'Z'];
        yield 'testParsingMultiPolygonMValue' => ['MULTIPOLYGONM(((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0),(5 5 1,7 5 1,7 7 1,5 7 1,5 5 1)),((1 1 0, 3 1 0, 3 3 0, 1 3 0, 1 1 0)))', null, [[[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1], [5, 5, 1]]], [[[1, 1, 0], [3, 1, 0], [3, 3, 0], [1, 3, 0], [1, 1, 0]]]], 'M'];
        yield 'testParsingMultiPolygonZMValue' => ['MULTIPOLYGONZM(((0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1,0 0 0 1),(5 5 1 2,7 5 1 2,7 7 1 2,5 7 1 2,5 5 1 2)),((1 1 0 3, 3 1 0 3, 3 3 0 3, 1 3 0 3, 1 1 0 3)))', null, [[[[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1], [0, 0, 0, 1]], [[5, 5, 1, 2], [7, 5, 1, 2], [7, 7, 1, 2], [5, 7, 1, 2], [5, 5, 1, 2]]], [[[1, 1, 0, 3], [3, 1, 0, 3], [3, 3, 0, 3], [1, 3, 0, 3], [1, 1, 0, 3]]]], 'ZM'];
        yield 'testParsingMultiPolygonValueWithSrid' => ['SRID=4326;MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))', 4326, [[[[0, 0], [10, 0], [10, 10], [0, 10], [0, 0]], [[5, 5], [7, 5], [7, 7], [5, 7], [5, 5]]], [[[1, 1], [3, 1], [3, 3], [1, 3], [1, 1]]]], null];
        yield 'testParsingMultiPolygonZValueWithSrid' => ['SRID=4326;MULTIPOLYGONZ(((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0),(5 5 1,7 5 1,7 7 1,5 7 1,5 5 1)),((1 1 0, 3 1 0, 3 3 0, 1 3 0, 1 1 0)))', 4326, [[[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1], [5, 5, 1]]], [[[1, 1, 0], [3, 1, 0], [3, 3, 0], [1, 3, 0], [1, 1, 0]]]], 'Z'];
        yield 'testParsingMultiPolygonMValueWithSrid' => ['SRID=4326;MULTIPOLYGONM(((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0),(5 5 1,7 5 1,7 7 1,5 7 1,5 5 1)),((1 1 0, 3 1 0, 3 3 0, 1 3 0, 1 1 0)))', 4326, [[[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]], [[5, 5, 1], [7, 5, 1], [7, 7, 1], [5, 7, 1], [5, 5, 1]]], [[[1, 1, 0], [3, 1, 0], [3, 3, 0], [1, 3, 0], [1, 1, 0]]]], 'M'];
        yield 'testParsingMultiPolygonZMValueWithSrid' => ['SRID=4326;MULTIPOLYGONZM(((0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1,0 0 0 1),(5 5 1 2,7 5 1 2,7 7 1 2,5 7 1 2,5 5 1 2)),((1 1 0 3, 3 1 0 3, 3 3 0 3, 1 3 0 3, 1 1 0 3)))', 4326, [[[[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1], [0, 0, 0, 1]], [[5, 5, 1, 2], [7, 5, 1, 2], [7, 7, 1, 2], [5, 7, 1, 2], [5, 5, 1, 2]]], [[[1, 1, 0, 3], [3, 1, 0, 3], [3, 3, 0, 3], [1, 3, 0, 3], [1, 1, 0, 3]]]], 'ZM'];
    }

    /**
     * @return \Generator<string, ?int, array<int|float>, ?string>
     */
    public static function pointProvider(): \Generator
    {
        yield 'testParsingPointValue' => ['POINT(34.23 -87)', null, [34.23, -87], null];
        yield 'testParsingPointZValue' => ['POINT(34.23 -87 10)', null, [34.23, -87, 10], 'Z'];
        yield 'testParsingPointDeclaredZValue' => ['POINTZ(34.23 -87 10)', null, [34.23, -87, 10], 'Z'];
        yield 'testParsingPointMValue' => ['POINTM(34.23 -87 10)', null, [34.23, -87, 10], 'M'];
        yield 'testParsingPointZMValue' => ['POINT(34.23 -87 10 30)', null, [34.23, -87, 10, 30], 'ZM'];
        yield 'testParsingPointDeclaredZMValue' => ['POINT ZM(34.23 -87 10 30)', null, [34.23, -87, 10, 30], 'ZM'];
        yield 'testParsingPointValueWithSrid' => ['SRID=4326;POINT(34.23 -87)', 4326, [34.23, -87], null];
        yield 'testParsingPointZValueWithSrid' => ['SRID=4326;POINT(34.23 -87 10)', 4326, [34.23, -87, 10], 'Z'];
        yield 'testParsingPointValueScientificWithSrid' => ['SRID=4326;POINT(4.23e-005 -8E-003)', 4326, [0.0000423, -0.008], null];
        yield 'testParsingPointValueScientific' => ['POINT(4.23e-005 -8E-003)', null, [0.0000423, -0.008], null];
        yield 'testParsingPointMValueWithSrid' => ['SRID=4326;POINTM(34.23 -87 10)', 4326, [34.23, -87, 10], 'M'];
        yield 'testParsingPointZMValueWithSrid' => ['SRID=4326;POINT(34.23 -87 10 30)', 4326, [34.23, -87, 10, 30], 'ZM'];
    }

    /**
     * @return \Generator<string, ?int, array<int|float>[], ?string>
     */
    public static function polygonProvider(): \Generator
    {
        yield 'testParsingPolygonValue' => ['POLYGON((0 0,10 0,10 10,0 10,0 0))', null, [[[0, 0], [10, 0], [10, 10], [0, 10], [0, 0]]], null];
        yield 'testParsingPolygonZValue' => ['POLYGON((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0))', null, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]]], 'Z'];
        yield 'testParsingPolygonMValue' => ['POLYGONM((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0))', null, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]]], 'M'];
        yield 'testParsingPolygonZMValue' => ['POLYGONZM((0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1,0 0 0 1))', null, [[[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1], [0, 0, 0, 1]]], 'ZM'];
        yield 'testParsingPolygonValueWithSrid' => ['SRID=4326;POLYGON((0 0,10 0,10 10,0 10,0 0))', 4326, [[[0, 0], [10, 0], [10, 10], [0, 10], [0, 0]]], null];
        yield 'testParsingPolygonValueMultiRing' => ['POLYGON((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5))', null, [[[0, 0], [10, 0], [10, 10], [0, 10], [0, 0]], [[5, 5], [7, 5], [7, 7], [5, 7], [5, 5]]], null];
        yield 'testParsingPolygonValueMultiRingWithSrid' => ['SRID=4326;POLYGON((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5))', 4326, [[[0, 0], [10, 0], [10, 10], [0, 10], [0, 0]], [[5, 5], [7, 5], [7, 7], [5, 7], [5, 5]]], null];
        yield 'testParsingPolygonZValueWithSrid' => ['SRID=4326;POLYGON((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0))', 4326, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]]], 'Z'];
        yield 'testParsingPolygonMValueWithSrid' => ['SRID=4326;POLYGONM((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0))', 4326, [[[0, 0, 0], [10, 0, 0], [10, 10, 0], [0, 10, 0], [0, 0, 0]]], 'M'];
        yield 'testParsingPolygonZMValueWithSrid' => ['SRID=4326;POLYGONZM((0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1,0 0 0 1))', 4326, [[[0, 0, 0, 1], [10, 0, 0, 1], [10, 10, 0, 1], [0, 10, 0, 1], [0, 0, 0, 1]]], 'ZM'];
    }

    /**
     * @return \Generator<string, string>
     */
    public static function unexpectedValues(): \Generator
    {
        yield 'testParsingGarbage' => ['@#_$%', '[Syntax Error] line 0, col 0: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_TYPE, got "@" in value "@#_$%"'];
        yield 'testParsingBadType' => ['PNT(10 10)', '[Syntax Error] line 0, col 0: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "PNT(10 10)"'];
        yield 'testParsingPointValueWithBadSrid' => ['SRID=432.6;POINT(34.23 -87)', '[Syntax Error] line 0, col 5: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "432.6" in value "SRID=432.6;POINT(34.23 -87)"'];
        yield 'testParsingPointValueMissingCoordinate' => ['POINT(34.23)', '[Syntax Error] line 0, col 11: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINT(34.23)"'];
        yield 'testParsingPointMValueMissingCoordinate' => ['POINTM(34.23 10)', '[Syntax Error] line 0, col 15: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINTM(34.23 10)"'];
        yield 'testParsingPointMValueExtraCoordinate' => ['POINTM(34.23 10 30 40)', '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "40" in value "POINTM(34.23 10 30 40)"'];
        yield 'testParsingPointZMValueMissingCoordinate' => ['POINTZM(34.23 10 45)', '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINTZM(34.23 10 45)"'];
        yield 'testParsingPointZMValueExtraCoordinate' => ['POINTZM(34.23 10 45 4.5 99)', '[Syntax Error] line 0, col 24: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "99" in value "POINTZM(34.23 10 45 4.5 99)"'];
        yield 'testParsingPointValueShortString' => ['POINT(34.23', '[Syntax Error] line 0, col -1: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got end of string. in value "POINT(34.23"'];
        yield 'testParsingPointValueWrongScientificWithSrid' => ['SRID=4326;POINT(4.23test-005 -8e-003)', '[Syntax Error] line 0, col 20: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "test" in value "SRID=4326;POINT(4.23test-005 -8e-003)"'];
        yield 'testParsingPointValueWithComma' => ['POINT(10, 10)', '[Syntax Error] line 0, col 8: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "," in value "POINT(10, 10)"'];
        yield 'testParsingLineStringValueMissingCoordinate' => ['LINESTRING(34.23 -87, 45.3)', '[Syntax Error] line 0, col 26: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "LINESTRING(34.23 -87, 45.3)"'];
        yield 'testParsingLineStringValueMismatchedDimensions' => ['LINESTRING(34.23 -87, 45.3 56 23.4)', '[Syntax Error] line 0, col 30: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "23.4" in value "LINESTRING(34.23 -87, 45.3 56 23.4)"'];
        yield 'testParsingPolygonValueMissingParenthesis' => ['POLYGON(0 0,10 0,10 10,0 10,0 0)', '[Syntax Error] line 0, col 8: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "0" in value "POLYGON(0 0,10 0,10 10,0 10,0 0)"'];
        yield 'testParsingPolygonValueMismatchedDimension' => ['POLYGON((0 0,10 0,10 10 10,0 10,0 0))', '[Syntax Error] line 0, col 24: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "10" in value "POLYGON((0 0,10 0,10 10 10,0 10,0 0))"'];
        yield 'testParsingPolygonValueMultiRingMissingComma' => ['POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))', '[Syntax Error] line 0, col 33: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))"'];
        yield 'testParsingMultiLineStringValueMissingComma' => ['MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))', '[Syntax Error] line 0, col 37: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))"'];
        yield 'testParsingMultiPolygonValueMissingParenthesis' => ['MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))', '[Syntax Error] line 0, col 64: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "1" in value "MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))"'];
        yield 'testParsingGeometryCollectionValueWithBadType' => ['GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))', '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))"'];
        yield 'testParsingGeometryCollectionValueWithMismatchedDimension' => ['GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30 10), LINESTRING(15 15, 20 20))', '[Syntax Error] line 0, col 45: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "10" in value "GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30 10), LINESTRING(15 15, 20 20))"'];
    }

    /**
     * @param array<int|float> $coordinates
     */
    #[DataProvider('geometryCollectionProvider')]
    public function testGeometryCollection(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        $parser = new Parser($value);
        $actual = $parser->parse();

        self::assertGeometryCollectionParsed($srid, $coordinates, $dimension, $actual);
    }

    /**
     * @param array<int|float> $coordinates
     */
    #[DataProvider('lineStringProvider')]
    public function testLineString(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        $parser = new Parser($value);
        $actual = $parser->parse();

        self::assertLineStringParsed($srid, $coordinates, $dimension, $actual);
    }

    /**
     * @param array<int|float> $coordinates
     */
    #[DataProvider('multiLineStringProvider')]
    public function testMultiLineString(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        $parser = new Parser($value);
        $actual = $parser->parse();

        self::assertMultiLineStringParsed($srid, $coordinates, $dimension, $actual);
    }

    /**
     * @param array<int|float> $coordinates
     */
    #[DataProvider('multiPointProvider')]
    public function testMultiPoint(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        $parser = new Parser($value);
        $actual = $parser->parse();

        self::assertMultiPointParsed($srid, $coordinates, $dimension, $actual);
    }

    /**
     * @param array<int|float> $coordinates
     */
    #[DataProvider('multiPolygonProvider')]
    public function testMultiPolygon(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        $parser = new Parser($value);
        $actual = $parser->parse();

        self::assertMultiPolygonParsed($srid, $coordinates, $dimension, $actual);
    }

    #[DataProvider('unexpectedValues')]
    public function testParserWithUnexpectedValues(string $value, string $exceptionMessage): void
    {
        $parser = new Parser($value);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $parser->parse();
    }

    /**
     * @param array<int|float> $coordinates
     */
    #[DataProvider('pointProvider')]
    public function testPoint(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        $parser = new Parser($value);
        $actual = $parser->parse();

        self::assertPointParsed($srid, $coordinates, $dimension, $actual);
    }

    /**
     * @param array<int|float> $coordinates
     */
    #[DataProvider('polygonProvider')]
    public function testPolygon(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        $parser = new Parser($value);
        $actual = $parser->parse();

        self::assertPolygonParsed($srid, $coordinates, $dimension, $actual);
    }

    public function testReusedParser(): void
    {
        $parser = new Parser();

        foreach (self::pointProvider() as $name => $testData) {
            $actual = $parser->parse($testData[0]);
            self::assertPointParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (self::lineStringProvider() as $name => $testData) {
            $actual = $parser->parse($testData[0]);
            self::assertLineStringParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (self::polygonProvider() as $name => $testData) {
            $actual = $parser->parse($testData[0]);
            self::assertPolygonParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (self::multiPointProvider() as $name => $testData) {
            $actual = $parser->parse($testData[0]);
            self::assertMultiPointParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (self::multiLineStringProvider() as $name => $testData) {
            $actual = $parser->parse($testData[0]);
            self::assertMultiLineStringParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (self::multiPolygonProvider() as $name => $testData) {
            $actual = $parser->parse($testData[0]);
            self::assertMultiPolygonParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (self::geometryCollectionProvider() as $name => $testData) {
            $actual = $parser->parse($testData[0]);
            self::assertGeometryCollectionParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
    }
}
