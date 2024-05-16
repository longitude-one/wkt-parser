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

class MultiPointTest extends SpecificTestCase
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
     * @param (int|string)[][] $coordinates
     */
    #[DataProvider('multiPointProvider')]
    public function testMultiPoint(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: (int|string)[][], srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($value);

        self::assertMultiPointParsed($srid, $coordinates, $dimension, $actual);
    }
}
