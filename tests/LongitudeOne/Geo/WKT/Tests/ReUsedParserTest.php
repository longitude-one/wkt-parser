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

use LongitudeOne\Geo\WKT\Parser;
use LongitudeOne\Geo\WKT\Tests\Utils\SpecificTestCase;

class ReUsedParserTest extends SpecificTestCase
{
    private Parser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new Parser();
    }

    protected function tearDown(): void
    {
        unset($this->parser);
        parent::tearDown();
    }

    public function testReusedParser(): void
    {
        foreach (PointTest::pointProvider() as $name => $testData) {
            /** @var array{type:string, value: (int|string)[], srid: ?int, dimension: ?string} $actual */
            $actual = $this->parser->parse($testData[0]);
            self::assertPointParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (LineStringTest::lineStringProvider() as $name => $testData) {
            /** @var array{type:string, value: (int|string)[][], srid: ?int, dimension: ?string} $actual */
            $actual = $this->parser->parse($testData[0]);
            self::assertLineStringParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (PolygonTest::polygonProvider() as $name => $testData) {
            /** @var array{type:string, value: (int|string)[][][], srid: ?int, dimension: ?string} $actual */
            $actual = $this->parser->parse($testData[0]);
            self::assertPolygonParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (MultiPointTest::multiPointProvider() as $name => $testData) {
            /** @var array{type:string, value: (int|string)[][], srid: ?int, dimension: ?string} $actual */
            $actual = $this->parser->parse($testData[0]);
            self::assertMultiPointParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (MultiLineStringTest::multiLineStringProvider() as $name => $testData) {
            /** @var array{type:string, value: (int|string)[][][], srid: ?int, dimension: ?string} $actual */
            $actual = $this->parser->parse($testData[0]);
            self::assertMultiLineStringParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (MultiPolygonTest::multiPolygonProvider() as $name => $testData) {
            /** @var array{type:string, value: (int|string)[][][][], srid: ?int, dimension: ?string} $actual */
            $actual = $this->parser->parse($testData[0]);
            self::assertMultiPolygonParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
        foreach (GeometryCollectionTest::geometryCollectionProvider() as $name => $testData) {
            /** @var array{type:string, value: array<array{'type': string, value:(int|string)[]|(int|string)[][]}>, srid: ?int, dimension: ?string} $actual */
            $actual = $this->parser->parse($testData[0]);
            self::assertGeometryCollectionParsed($testData[1], $testData[2], $testData[3], $actual, 'Failed dataset "'.$name.'"');
        }
    }
}
