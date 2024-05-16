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

class CircularStringTest extends SpecificTestCase
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
    public static function circularStringProvider(): \Generator
    {
        yield 'testCircularString' => ['CIRCULARSTRING(0 0, 1 1, 1 0)', null, [[0, 0], [1, 1], [1, 0]], null];
        yield 'testCircularStringWithFloat' => ['CIRCULARSTRING(0.0 0.0, 1.1 1.1, 1.0 0.0)', null, [['0', '0'], ['1.1', '1.1'], ['1', '0']], null];
        yield 'testCircularStringWithSrid' => ['SRID=4326;CIRCULARSTRING(0 0, 1 1, 1 0)', 4326, [[0, 0], [1, 1], [1, 0]], null];
        yield 'testCircularStringWithZ' => ['CIRCULARSTRINGZ(0 0 0, 1 1 1, 1 0 -1)', null, [[0, 0, 0], [1, 1, 1], [1, 0, -1]], 'Z'];
        yield 'testCircularStringWithZAndSrid' => ['SRID=4326;CIRCULARSTRINGZ(0 0 0, 1 1 1, 1 0 -1)', 4326, [[0, 0, 0], [1, 1, 1], [1, 0, -1]], 'Z'];
        yield 'testCircularStringWithM' => ['CIRCULARSTRINGM(0 0 0, 1 1 1, 1 0 -1)', null, [[0, 0, 0], [1, 1, 1], [1, 0, -1]], 'M'];
        yield 'testCircularStringWithMAndSrid' => ['SRID=4326;CIRCULARSTRINGM(0 0 0, 1 1 1, 1 0 -1)', 4326, [[0, 0, 0], [1, 1, 1], [1, 0, -1]], 'M'];
        yield 'testCircularStringWithZM' => ['CIRCULARSTRINGZM(0 0 0 0, 1 1 1 1, 1 0 -1 0)', null, [[0, 0, 0, 0], [1, 1, 1, 1], [1, 0, -1, 0]], 'ZM'];
        yield 'testCircularStringWithZMAndSrid' => ['SRID=4326;CIRCULARSTRINGZM(0 0 0 0, 1 1 1 1.2, 1 0 -1 0)', 4326, [[0, 0, 0, 0], [1, 1, 1, '1.2'], [1, 0, -1, 0]], 'ZM'];
    }

    /**
     * @param (int|string)[][] $coordinates
     */
    #[DataProvider('circularStringProvider')]
    public function testCircularString(string $value, ?int $srid, array $coordinates, ?string $dimension): void
    {
        /** @var array{type:string, value: (int|string)[][], srid: ?int, dimension: ?string} $actual */
        $actual = $this->parser->parse($value);
        self::assertCircularStringParsed($srid, $coordinates, $dimension, $actual);
    }
}
