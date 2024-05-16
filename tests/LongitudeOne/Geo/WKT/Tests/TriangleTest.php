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

class TriangleTest extends SpecificTestCase
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
    public static function triangleProvider(): \Generator
    {
        yield 'testParsingTriangleValue' => ['TRIANGLE((34.23 -87, 45.3 -92, 10 10, 34.23 -87))', null, [['34.23', -87], ['45.3', -92], [10, 10], ['34.23', -87]], null];
        yield 'testParsingTriangleZValue' => ['TRIANGLEZ((34.23 -87 33, 45.3 -92 22, 10 10 11, 34.23 -87 33))', null, [['34.23', -87, 33], ['45.3', -92, 22], [10, 10, 11], ['34.23', -87, 33]], 'Z'];
        yield 'testParsingTriangleMValue' => ['TRIANGLEM((34.23 -87 10, 45.3 -92 10, 10 10 11, 34.23 -87 10))', null, [['34.23', -87, 10], ['45.3', -92, 10], [10, 10, 11], ['34.23', -87, 10]], 'M'];
        yield 'testParsingTriangleZMValue' => ['TRIANGLEZM((34.23 -87 10 20, 45.3 -92 10 20, 10 11 12 13, 34.23 -87 10 20))', null, [['34.23', -87, 10, 20], ['45.3', -92, 10, 20], [10, 11, 12, 13], ['34.23', -87, 10, 20]], 'ZM'];
        yield 'testParsingTriangleValueWithSrid' => ['SRID=4326;TRIANGLE((34.23 -87, 45.3 -92, 10 10, 34.23 -87))', 4326, [['34.23', -87], ['45.3', -92], [10, 10], ['34.23', -87]], null];
        yield 'testParsingTriangleZValueWithSrid' => ['SRID=4326;TRIANGLE((34.23 -87 33, 45.3 -92 22, 10 10 11, 34.23 -87 33))', 4326, [['34.23', -87, 33], ['45.3', -92, 22], [10, 10, 11], ['34.23', -87, 33]], 'Z'];
        yield 'testParsingTriangleMValueWithSrid' => ['SRID=4326;TRIANGLEM((34.23 -87 10, 45.3 -92 10, 10 10 11, 34.23 -87 10))', 4326, [['34.23', -87, 10], ['45.3', -92, 10], [10, 10, 11], ['34.23', -87, 10]], 'M'];
        yield 'testParsingTriangleZMValueWithSrid' => ['SRID=4326;TRIANGLEZM((34.23 -87 10 20, 45.3 -92 10 20, 10 11 12 13, 34.23 -87 10 20))', 4326, [['34.23', -87, 10, 20], ['45.3', -92, 10, 20], [10, 11, 12, 13], ['34.23', -87, 10, 20]], 'ZM'];
    }

    /**
     * @param (int|string)[][] $coordinates
     */
    #[DataProvider('triangleProvider')]
    public function testTriangle(string $actualValue, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: (int|string)[][], srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($actualValue);

        self::assertTriangleParsed($srid, $coordinates, $dimension, $actual);
    }
}
