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

class LineStringTest extends SpecificTestCase
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
     * @return \Generator<string, array{0: string, 1: ?int, 2: (int|string)[][], 3: ?string}, null, void>
     */
    public static function lineStringProvider(): \Generator
    {
        yield 'testParsingLineStringValue' => ['LINESTRING(34.23 -87, 45.3 -92)', null, [['34.23', -87], ['45.3', -92]], null];
        yield 'testParsingLineStringZValue' => ['LINESTRING(34.23 -87 10, 45.3 -92 10)', null, [['34.23', -87, 10], ['45.3', -92, 10]], 'Z'];
        yield 'testParsingLineStringMValue' => ['LINESTRINGM(34.23 -87 10, 45.3 -92 10)', null, [['34.23', -87, 10], ['45.3', -92, 10]], 'M'];
        yield 'testParsingLineStringZMValue' => ['LINESTRINGZM(34.23 -87 10 20, 45.3 -92 10 20)', null, [['34.23', -87, 10, 20], ['45.3', -92, 10, 20]], 'ZM'];
        yield 'testParsingLineStringValueWithSrid' => ['SRID=4326;LINESTRING(34.23 -87, 45.3 -92)', 4326, [['34.23', -87], ['45.3', -92]], null];
        yield 'testParsingLineStringZValueWithSrid' => ['SRID=4326;LINESTRING(34.23 -87 10, 45.3 -92 10)', 4326, [['34.23', -87, 10], ['45.3', -92, 10]], 'Z'];
        yield 'testParsingLineStringMValueWithSrid' => ['SRID=4326;LINESTRINGM(34.23 -87 10, 45.3 -92 10)', 4326, [['34.23', -87, 10], ['45.3', -92, 10]], 'M'];
        yield 'testParsingLineStringZMValueWithSrid' => ['SRID=4326;LINESTRINGZM(34.23 -87 10 20, 45.3 -92 10 20)', 4326, [['34.23', -87, 10, 20], ['45.3', -92, 10, 20]], 'ZM'];
    }

    /**
     * @param (int|string)[][] $coordinates
     */
    #[DataProvider('lineStringProvider')]
    public function testLineString(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: (int|string)[][], srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($value);

        self::assertLineStringParsed($srid, $coordinates, $dimension, $actual);
    }
}
