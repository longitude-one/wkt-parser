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
     * This method provide triangles with not enough points or too much points.
     *
     * @return \Generator<string, array{0: string, 1: string}, null, void>
     */
    public static function badNumberPointsProvider(): \Generator
    {
        // With 1 point
        yield 'testParsingTriangleWith1Point' => ['TRIANGLE((34.23 -87))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 1.'];
        yield 'testParsingTriangleZWith1Point' => ['TRIANGLEZ((34.23 -87 33))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 1.'];
        yield 'testParsingTriangleMWith1Point' => ['TRIANGLEM((34.23 -87 10))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 1.'];
        yield 'testParsingTriangleZMWith1Point' => ['TRIANGLEZM((34.23 -87 10 20))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 1.'];

        // With 2 points
        yield 'testParsingTriangleWith2Points' => ['TRIANGLE((34.23 -87, 45.3 -92))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 2.'];
        yield 'testParsingTriangleZWith2Points' => ['TRIANGLEZ((34.23 -87 33, 45.3 -92 22))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 2.'];
        yield 'testParsingTriangleMWith2Points' => ['TRIANGLEM((34.23 -87 10, 45.3 -92 10))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 2.'];
        yield 'testParsingTriangleZMWith2Points' => ['TRIANGLEZM((34.23 -87 10 20, 45.3 -92 10 20))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 2.'];

        // With 3 points
        yield 'testParsingTriangleWith3Points' => ['TRIANGLE((34.23 -87, 45.3 -92, 10 10))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 3.'];
        yield 'testParsingTriangleZWith3Points' => ['TRIANGLEZ((34.23 -87 33, 45.3 -92 22, 10 10 11))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 3.'];
        yield 'testParsingTriangleMWith3Points' => ['TRIANGLEM((34.23 -87 10, 45.3 -92 10, 10 10 11))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 3.'];
        yield 'testParsingTriangleZMWith3Points' => ['TRIANGLEZM((34.23 -87 10 20, 45.3 -92 10 20, 10 11 12 13))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 3.'];

        // With 5 points
        yield 'testParsingTriangleWith5Points' => ['TRIANGLE((34.23 -87, 45.3 -92, 10 10, 34.23 -87, 45.3 -92))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 5.'];
        yield 'testParsingTriangleZWith5Points' => ['TRIANGLEZ((34.23 -87 33, 45.3 -92 22, 10 10 11, 34.23 -87 33, 45.3 -92 22))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 5.'];
        yield 'testParsingTriangleMWith5Points' => ['TRIANGLEM((34.23 -87 10, 45.3 -92 10, 10 10 11, 34.23 -87 10, 45.3 -92 10))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 5.'];
        yield 'testParsingTriangleZMWith5Points' => ['TRIANGLEZM((34.23 -87 10 20, 45.3 -92 10 20, 10 11 12 13, 34.23 -87 10 20, 45.3 -92 10 20))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points, you provided 5.'];
    }

    /**
     * This method provide not closed triangles.
     * According to the ISO-13249 specification, a triangle is a closed ring with fourth points.
     * Last point shall be the same as the first one.
     *
     * @return \Generator<string, array{0: string}, null, void>
     */
    public static function notClosedTriangleProvider(): \Generator
    {
        yield 'testParsingTriangleWithNotClosedRing' => ['TRIANGLE((34.23 -87, 45.3 -92, 10 10, 0 0))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points. Your first point is "34.23 -87", the fourth is "0 0"'];
        yield 'testParsingTriangleZWithNotClosedRing' => ['TRIANGLEZ((34.23 -87 33, 45.3 -92 22, 10 10 11, 0 0 0))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points. Your first point is "34.23 -87 33", the fourth is "0 0 0"'];
        yield 'testParsingTriangleMWithNotClosedRing' => ['TRIANGLEM((34.23 -87 10, 45.3 -92 10, 10 10 11, 0 0 0))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points. Your first point is "34.23 -87 10", the fourth is "0 0 0"'];
        yield 'testParsingTriangleZMWithNotClosedRing' => ['TRIANGLEZM((34.23 -87 10 20, 45.3 -92 10 20, 10 11 12 13, 0 0 0 0))', 'According to the ISO-13249 specification, a triangle is a closed ring with fourth points. Your first point is "34.23 -87 10 20", the fourth is "0 0 0 0"'];
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

    #[DataProvider('badNumberPointsProvider')]
    public function testBadNumberPoints(string $actualValue, string $expectedMessage): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->parser->parse($actualValue);
    }

    #[DataProvider('notClosedTriangleProvider')]
    public function testNotClosedTriangle(string $actualValue, string $expectedMessage): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage($expectedMessage);
        $this->parser->parse($actualValue);
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
