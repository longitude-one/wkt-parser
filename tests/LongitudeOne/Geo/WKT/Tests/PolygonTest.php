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
use PHPUnit\Framework\Attributes\DataProvider;

class PolygonTest extends SpecificTestCase
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

    /**
     * @return \Generator<string, array{0: string, 1: ?int, 2: (int|string)[][][], 3: ?string}, null, void>
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
     * @param (int|string)[][][] $coordinates
     */
    #[DataProvider('polygonProvider')]
    public function testPolygon(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: (int|string)[][][], srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($value);

        self::assertPolygonParsed($srid, $coordinates, $dimension, $actual);
    }
}
