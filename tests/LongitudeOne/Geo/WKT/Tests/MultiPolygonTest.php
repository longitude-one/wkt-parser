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

class MultiPolygonTest extends SpecificTestCase
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
     * @return \Generator<string, array{0: string, 1: ?int, 2: (int|string)[][][][], 3: ?string}, null, void>
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
     * @param (int|string)[][][][] $coordinates
     */
    #[DataProvider('multiPolygonProvider')]
    public function testMultiPolygon(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: (int|string)[][][][], srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($value);

        self::assertMultiPolygonParsed($srid, $coordinates, $dimension, $actual);
    }
}
