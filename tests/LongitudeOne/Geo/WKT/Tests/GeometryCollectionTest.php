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

class GeometryCollectionTest extends SpecificTestCase
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
     * @return \Generator<string, array{0:string, 1:int|null, 2: array<array{'type': string, value:(int|string)[]|(int|string)[][]}>, 3: ?string}, null, void>
     */
    public static function geometryCollectionProvider(): \Generator
    {
        yield 'testGeometryCollection' => ['GEOMETRYCOLLECTION(POINT(34.23 -87), LINESTRING(34.23 -87, 45.3 -92))', null, [['type' => 'POINT', 'value' => ['34.23', -87]], ['type' => 'LINESTRING', 'value' => [['34.23', -87], ['45.3', -92]]]], null];
        yield 'testGeometryCollectionWithSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT(34.23 -87), LINESTRING(34.23 -87, 45.3 -92))', 4326, [['type' => 'POINT', 'value' => ['34.23', -87]], ['type' => 'LINESTRING', 'value' => [['34.23', -87], ['45.3', -92]]]], null];
        yield 'testGeometryCollectionWithZ' => ['GEOMETRYCOLLECTION(POINT Z(34.23 -87 10), LINESTRING Z(34.23 -87 10, 45.3 -92 10))', null, [['type' => 'POINT', 'value' => ['34.23', -87, 10]], ['type' => 'LINESTRING', 'value' => [['34.23', -87, 10], ['45.3', -92, 10]]]], 'Z'];
        yield 'testGeometryCollectionWithZAndSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT Z(34.23 -87 10), LINESTRING Z(34.23 -87 10, 45.3 -92 10))', 4326, [['type' => 'POINT', 'value' => ['34.23', -87, 10]], ['type' => 'LINESTRING', 'value' => [['34.23', -87, 10], ['45.3', -92, 10]]]], 'Z'];
        yield 'testGeometryCollectionWithM' => ['GEOMETRYCOLLECTION(POINT M(34.23 -87 10), LINESTRING M(34.23 -87 10, 45.3 -92 10))', null, [['type' => 'POINT', 'value' => ['34.23', -87, 10]], ['type' => 'LINESTRING', 'value' => [['34.23', -87, 10], ['45.3', -92, 10]]]], 'M'];
        yield 'testGeometryCollectionWithMAndSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT M(34.23 -87 10), LINESTRING M(34.23 -87 10, 45.3 -92 10))', 4326, [['type' => 'POINT', 'value' => ['34.23', -87, 10]], ['type' => 'LINESTRING', 'value' => [['34.23', -87, 10], ['45.3', -92, 10]]]], 'M'];
        yield 'testGeometryCollectionWithZM' => ['GEOMETRYCOLLECTION(POINT ZM(34.23 -87 10 20), LINESTRING ZM(34.23 -87 10 20, 45.3 -92 10 20))', null, [['type' => 'POINT', 'value' => ['34.23', -87, 10, 20]], ['type' => 'LINESTRING', 'value' => [['34.23', -87, 10, 20], ['45.3', -92, 10, 20]]]], 'ZM'];
        yield 'testGeometryCollectionWithZMAndSrid' => ['SRID=4326;GEOMETRYCOLLECTION(POINT ZM(34.23 -87 10 20), LINESTRING ZM(34.23 -87 10 20, 45.3 -92 10 20))', 4326, [['type' => 'POINT', 'value' => ['34.23', -87, 10, 20]], ['type' => 'LINESTRING', 'value' => [['34.23', -87, 10, 20], ['45.3', -92, 10, 20]]]], 'ZM'];
    }

    /**
     * @param array<array{'type': string, value:(int|string)[]|(int|string)[][]}> $coordinates
     */
    #[DataProvider('geometryCollectionProvider')]
    public function testGeometryCollection(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: array<array{'type': string, value:(int|string)[]|(int|string)[][]}>, srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($value);

        self::assertGeometryCollectionParsed($srid, $coordinates, $dimension, $actual);
    }
}
