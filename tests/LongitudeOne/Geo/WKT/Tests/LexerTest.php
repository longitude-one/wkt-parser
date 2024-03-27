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

use LongitudeOne\Geo\WKT\Lexer;
use PHPUnit\Framework\TestCase;

/**
 * Lexer tests.
 */
class LexerTest extends TestCase
{
    /**
     * @return \Generator{string, array{int, string, int}[]}
     */
    public static function tokenData(): \Generator
    {
        yield 'POINT' => ['POINT', [[Lexer::T_POINT, 'POINT', 0]]];
        yield 'POINTM' => ['POINTM', [[Lexer::T_POINT, 'POINT', 0], [Lexer::T_M, 'M', 5]]];
        yield 'POINT M' => ['POINT M', [[Lexer::T_POINT, 'POINT', 0], [Lexer::T_M, 'M', 6]]];
        yield 'POINTZ' => ['POINTZ', [[Lexer::T_POINT, 'POINT', 0], [Lexer::T_Z, 'Z', 5]]];
        yield 'POINT Z' => ['POINT Z', [[Lexer::T_POINT, 'POINT', 0], [Lexer::T_Z, 'Z', 6]]];
        yield 'POINT ZM' => ['POINT ZM', [[Lexer::T_POINT, 'POINT', 0], [Lexer::T_ZM, 'ZM', 6]]];
        yield 'POINTZM' => ['POINTZM', [[Lexer::T_POINT, 'POINT', 0], [Lexer::T_ZM, 'ZM', 5]]];

        yield 'LINESTRING' => ['LINESTRING', [[Lexer::T_LINESTRING, 'LINESTRING', 0]]];
        yield 'LINESTRINGM' => ['LINESTRINGM', [[Lexer::T_LINESTRING, 'LINESTRING', 0], [Lexer::T_M, 'M', 10]]];
        yield 'LINESTRING M' => ['LINESTRING M', [[Lexer::T_LINESTRING, 'LINESTRING', 0], [Lexer::T_M, 'M', 11]]];
        yield 'LINESTRINGZ' => ['LINESTRINGZ', [[Lexer::T_LINESTRING, 'LINESTRING', 0], [Lexer::T_Z, 'Z', 10]]];
        yield 'LINESTRING Z' => ['LINESTRING Z', [[Lexer::T_LINESTRING, 'LINESTRING', 0], [Lexer::T_Z, 'Z', 11]]];
        yield 'LINESTRING ZM' => ['LINESTRING ZM', [[Lexer::T_LINESTRING, 'LINESTRING', 0], [Lexer::T_ZM, 'ZM', 11]]];
        yield 'LINESTRINGZM' => ['LINESTRINGZM', [[Lexer::T_LINESTRING, 'LINESTRING', 0], [Lexer::T_ZM, 'ZM', 10]]];

        yield 'POLYGON' => ['POLYGON', [[Lexer::T_POLYGON, 'POLYGON', 0]]];
        yield 'POLYGONM' => ['POLYGONM', [[Lexer::T_POLYGON, 'POLYGON', 0], [Lexer::T_M, 'M', 7]]];
        yield 'POLYGON M' => ['POLYGON M', [[Lexer::T_POLYGON, 'POLYGON', 0], [Lexer::T_M, 'M', 8]]];
        yield 'POLYGONZ' => ['POLYGONZ', [[Lexer::T_POLYGON, 'POLYGON', 0], [Lexer::T_Z, 'Z', 7]]];
        yield 'POLYGON Z' => ['POLYGON Z', [[Lexer::T_POLYGON, 'POLYGON', 0], [Lexer::T_Z, 'Z', 8]]];
        yield 'POLYGON ZM' => ['POLYGON ZM', [[Lexer::T_POLYGON, 'POLYGON', 0], [Lexer::T_ZM, 'ZM', 8]]];
        yield 'POLYGONZM' => ['POLYGONZM', [[Lexer::T_POLYGON, 'POLYGON', 0], [Lexer::T_ZM, 'ZM', 7]]];

        yield 'MULTIPOINT' => ['MULTIPOINT', [[Lexer::T_MULTIPOINT, 'MULTIPOINT', 0]]];
        yield 'MULTIPOINTM' => ['MULTIPOINTM', [[Lexer::T_MULTIPOINT, 'MULTIPOINT', 0], [Lexer::T_M, 'M', 10]]];
        yield 'MULTIPOINT M' => ['MULTIPOINT M', [[Lexer::T_MULTIPOINT, 'MULTIPOINT', 0], [Lexer::T_M, 'M', 11]]];
        yield 'MULTIPOINTZ' => ['MULTIPOINTZ', [[Lexer::T_MULTIPOINT, 'MULTIPOINT', 0], [Lexer::T_Z, 'Z', 10]]];
        yield 'MULTIPOINT Z' => ['MULTIPOINT Z', [[Lexer::T_MULTIPOINT, 'MULTIPOINT', 0], [Lexer::T_Z, 'Z', 11]]];
        yield 'MULTIPOINT ZM' => ['MULTIPOINT ZM', [[Lexer::T_MULTIPOINT, 'MULTIPOINT', 0], [Lexer::T_ZM, 'ZM', 11]]];
        yield 'MULTIPOINTZM' => ['MULTIPOINTZM', [[Lexer::T_MULTIPOINT, 'MULTIPOINT', 0], [Lexer::T_ZM, 'ZM', 10]]];

        yield 'MULTILINESTRING' => ['MULTILINESTRING', [[Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0]]];
        yield 'MULTILINESTRINGM' => ['MULTILINESTRINGM', [[Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0], [Lexer::T_M, 'M', 15]]];
        yield 'MULTILINESTRING M' => ['MULTILINESTRING M', [[Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0], [Lexer::T_M, 'M', 16]]];
        yield 'MULTILINESTRINGZ' => ['MULTILINESTRINGZ', [[Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0], [Lexer::T_Z, 'Z', 15]]];
        yield 'MULTILINESTRING Z' => ['MULTILINESTRING Z', [[Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0], [Lexer::T_Z, 'Z', 16]]];
        yield 'MULTILINESTRING ZM' => ['MULTILINESTRING ZM', [[Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0], [Lexer::T_ZM, 'ZM', 16]]];

        yield 'MULTIPOLYGON' => ['MULTIPOLYGON', [[Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0]]];
        yield 'MULTIPOLYGONM' => ['MULTIPOLYGONM', [[Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0], [Lexer::T_M, 'M', 12]]];
        yield 'MULTIPOLYGON M' => ['MULTIPOLYGON M', [[Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0], [Lexer::T_M, 'M', 13]]];
        yield 'MULTIPOLYGONZ' => ['MULTIPOLYGONZ', [[Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0], [Lexer::T_Z, 'Z', 12]]];
        yield 'MULTIPOLYGON Z' => ['MULTIPOLYGON Z', [[Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0], [Lexer::T_Z, 'Z', 13]]];
        yield 'MULTIPOLYGON ZM' => ['MULTIPOLYGON ZM', [[Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0], [Lexer::T_ZM, 'ZM', 13]]];

        yield 'GEOMETRYCOLLECTION' => ['GEOMETRYCOLLECTION', [[Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0]]];
        yield 'GEOMETRYCOLLECTIONM' => ['GEOMETRYCOLLECTIONM', [[Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0], [Lexer::T_M, 'M', 18]]];
        yield 'GEOMETRYCOLLECTION M' => ['GEOMETRYCOLLECTION M', [[Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0], [Lexer::T_M, 'M', 19]]];
        yield 'GEOMETRYCOLLECTIONZ' => ['GEOMETRYCOLLECTIONZ', [[Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0], [Lexer::T_Z, 'Z', 18]]];
        yield 'GEOMETRYCOLLECTION Z' => ['GEOMETRYCOLLECTION Z', [[Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0], [Lexer::T_Z, 'Z', 19]]];
        yield 'GEOMETRYCOLLECTION ZM' => ['GEOMETRYCOLLECTION ZM', [[Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0], [Lexer::T_ZM, 'ZM', 19]]];
        yield 'GEOMETRYCOLLECTIONZM' => ['GEOMETRYCOLLECTIONZM', [[Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0], [Lexer::T_ZM, 'ZM', 18]]];

        yield 'COMPOUNDCURVE' => ['COMPOUNDCURVE', [[Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0]]];
        yield 'COMPOUNDCURVEM' => ['COMPOUNDCURVEM', [[Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0], [Lexer::T_M, 'M', 13]]];
        yield 'COMPOUNDCURVE M' => ['COMPOUNDCURVE M', [[Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0], [Lexer::T_M, 'M', 14]]];
        yield 'COMPOUNDCURVEZ' => ['COMPOUNDCURVEZ', [[Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0], [Lexer::T_Z, 'Z', 13]]];
        yield 'COMPOUNDCURVE Z' => ['COMPOUNDCURVE Z', [[Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0], [Lexer::T_Z, 'Z', 14]]];
        yield 'COMPOUNDCURVE ZM' => ['COMPOUNDCURVE ZM', [[Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0], [Lexer::T_ZM, 'ZM', 14]]];
        yield 'COMPOUNDCURVEZM' => ['COMPOUNDCURVEZM', [[Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0], [Lexer::T_ZM, 'ZM', 13]]];

        yield 'CIRCULARSTRING' => ['CIRCULARSTRING', [[Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0]]];
        yield 'CIRCULARSTRINGM' => ['CIRCULARSTRINGM', [[Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0], [Lexer::T_M, 'M', 14]]];
        yield 'CIRCULARSTRING M' => ['CIRCULARSTRING M', [[Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0], [Lexer::T_M, 'M', 15]]];
        yield 'CIRCULARSTRINGZ' => ['CIRCULARSTRINGZ', [[Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0], [Lexer::T_Z, 'Z', 14]]];
        yield 'CIRCULARSTRING Z' => ['CIRCULARSTRING Z', [[Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0], [Lexer::T_Z, 'Z', 15]]];
        yield 'CIRCULARSTRING ZM' => ['CIRCULARSTRING ZM', [[Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0], [Lexer::T_ZM, 'ZM', 15]]];

        yield '35' => ['35', [[Lexer::T_INTEGER, 35, 0]]];
        yield '-25' => ['-25', [[Lexer::T_INTEGER, -25, 0]]];
        yield '-120.33' => ['-120.33', [[Lexer::T_FLOAT, -120.33, 0]]];
        yield '0.0' => ['0.0', [[Lexer::T_FLOAT, 0.0, 0]]];

        yield 'SRID' => ['SRID', [[Lexer::T_SRID, 'SRID', 0]]];
        yield 'SRID=4326;LINESTRING(0 0.0, 10.1 -10.025, 20.5 25.9, 53E-003 60)' => [
            'SRID=4326;LINESTRING(0 0.0, 10.1 -10.025, 20.5 25.9, 53E-003 60)',
            [
                [Lexer::T_SRID, 'SRID', 0],
                [Lexer::T_EQUALS, '=', 4],
                [Lexer::T_INTEGER, 4326, 5],
                [Lexer::T_SEMICOLON, ';', 9],
                [Lexer::T_LINESTRING, 'LINESTRING', 10],
                [Lexer::T_OPEN_PARENTHESIS, '(', 20],
                [Lexer::T_INTEGER, 0, 21],
                [Lexer::T_FLOAT, 0, 23],
                [Lexer::T_COMMA, ',', 26],
                [Lexer::T_FLOAT, 10.1, 28],
                [Lexer::T_FLOAT, -10.025, 33],
                [Lexer::T_COMMA, ',', 40],
                [Lexer::T_FLOAT, 20.5, 42],
                [Lexer::T_FLOAT, 25.9, 47],
                [Lexer::T_COMMA, ',', 51],
                [Lexer::T_FLOAT, 0.053, 53],
                [Lexer::T_INTEGER, 60, 61],
                [Lexer::T_CLOSE_PARENTHESIS, ')', 63],
            ],
        ];
    }

    /**
     * @param array{int, string, int}[] $expected
     * @dataProvider tokenData
     */
    public function testTokenRecognition(string $value, array $expected): void
    {
        $lexer = new Lexer($value);

        foreach ($expected as $token) {
            $lexer->moveNext();

            $actual = $lexer->lookahead;

            $this->assertEquals($token[0], $actual->type);
            $this->assertEquals($token[1], $actual->value);
            $this->assertEquals($token[2], $actual->position);
        }
    }

    public function testTokenRecognitionReuseLexer(): void
    {
        $lexer = new Lexer();

        foreach (self::tokenData() as $testData) {
            $lexer->setInput($testData[0]);

            foreach ($testData[1] as $token) {
                $lexer->moveNext();

                $actual = $lexer->lookahead;

                $this->assertEquals($token[0], $actual->type);
                $this->assertEquals($token[1], $actual->value);
                $this->assertEquals($token[2], $actual->position);
            }
        }
    }
}
