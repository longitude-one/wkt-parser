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

class MultiLineStringTest extends SpecificTestCase
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
     * @param (int|string)[][][] $coordinates
     */
    #[DataProvider('multiLineStringProvider')]
    public function testMultiLineString(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: (int|string)[][][], srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($value);

        self::assertMultiLineStringParsed($srid, $coordinates, $dimension, $actual);
    }
}
