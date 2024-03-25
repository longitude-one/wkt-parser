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
     * @return array
     */
    public static function tokenData()
    {
        return [
            'POINT' => [
                'value' => 'POINT',
                'expected' => [
                    [Lexer::T_POINT, 'POINT', 0],
                ],
            ],
            'POINTM' => [
                'value' => 'POINTM',
                'expected' => [
                    [Lexer::T_POINT, 'POINT', 0],
                    [Lexer::T_M, 'M', 5],
                ],
            ],
            'POINT M' => [
                'value' => 'POINTM',
                'expected' => [
                    [Lexer::T_POINT, 'POINT', 0],
                    [Lexer::T_M, 'M', 5],
                ],
            ],
            'POINTZ' => [
                'value' => 'POINTZ',
                'expected' => [
                    [Lexer::T_POINT, 'POINT', 0],
                    [Lexer::T_Z, 'Z', 5],
                ],
            ],
            'POINT Z' => [
                'value' => 'POINT Z',
                'expected' => [
                    [Lexer::T_POINT, 'POINT', 0],
                    [Lexer::T_Z, 'Z', 6],
                ],
            ],
            'POINT ZM' => [
                'value' => 'POINT ZM',
                'expected' => [
                    [Lexer::T_POINT, 'POINT', 0],
                    [Lexer::T_ZM, 'ZM', 6],
                ],
            ],
            'POINTZM' => [
                'value' => 'POINTZM',
                'expected' => [
                    [Lexer::T_POINT, 'POINT', 0],
                    [Lexer::T_ZM, 'ZM', 5],
                ],
            ],
            'LINESTRING' => [
                'value' => 'LINESTRING',
                'expected' => [
                    [Lexer::T_LINESTRING, 'LINESTRING', 0],
                ],
            ],
            'LINESTRINGM' => [
                'value' => 'LINESTRINGM',
                'expected' => [
                    [Lexer::T_LINESTRING, 'LINESTRING', 0],
                    [Lexer::T_M, 'M', 10],
                ],
            ],
            'POLYGON' => [
                'value' => 'POLYGON',
                'expected' => [
                    [Lexer::T_POLYGON, 'POLYGON', 0],
                ],
            ],
            'POLYGONM' => [
                'value' => 'POLYGONM',
                'expected' => [
                    [Lexer::T_POLYGON, 'POLYGON', 0],
                    [Lexer::T_M, 'M', 7],
                ],
            ],
            'MULTIPOINT' => [
                'value' => 'MULTIPOINT',
                'expected' => [
                    [Lexer::T_MULTIPOINT, 'MULTIPOINT', 0],
                ],
            ],
            'MULTIPOINTM' => [
                'value' => 'MULTIPOINTM',
                'expected' => [
                    [Lexer::T_MULTIPOINT, 'MULTIPOINT', 0],
                    [Lexer::T_M, 'M', 10],
                ],
            ],
            'MULTILINESTRING' => [
                'value' => 'MULTILINESTRING',
                'expected' => [
                    [Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0],
                ],
            ],
            'MULTILINESTRINGM' => [
                'value' => 'MULTILINESTRINGM',
                'expected' => [
                    [Lexer::T_MULTILINESTRING, 'MULTILINESTRING', 0],
                    [Lexer::T_M, 'M', 15],
                ],
            ],
            'MULTIPOLYGON' => [
                'value' => 'MULTIPOLYGON',
                'expected' => [
                    [Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0],
                ],
            ],
            'MULTIPOLYGONM' => [
                'value' => 'MULTIPOLYGONM',
                'expected' => [
                    [Lexer::T_MULTIPOLYGON, 'MULTIPOLYGON', 0],
                    [Lexer::T_M, 'M', 12],
                ],
            ],
            'GEOMETRYCOLLECTION' => [
                'value' => 'GEOMETRYCOLLECTION',
                'expected' => [
                    [Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0],
                ],
            ],
            'GEOMETRYCOLLECTIONM' => [
                'value' => 'GEOMETRYCOLLECTIONM',
                'expected' => [
                    [Lexer::T_GEOMETRYCOLLECTION, 'GEOMETRYCOLLECTION', 0],
                    [Lexer::T_M, 'M', 18],
                ],
            ],
            'COMPOUNDCURVE' => [
                'value' => 'COMPOUNDCURVE',
                'expected' => [
                    [Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0],
                ],
            ],
            'COMPOUNDCURVEM' => [
                'value' => 'COMPOUNDCURVEM',
                'expected' => [
                    [Lexer::T_COMPOUNDCURVE, 'COMPOUNDCURVE', 0],
                    [Lexer::T_M, 'M', 13],
                ],
            ],
            'CIRCULARSTRING' => [
                'value' => 'CIRCULARSTRING',
                'expected' => [
                    [Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0],
                ],
            ],
            'CIRCULARSTRINGM' => [
                'value' => 'CIRCULARSTRINGM',
                'expected' => [
                    [Lexer::T_CIRCULARSTRING, 'CIRCULARSTRING', 0],
                    [Lexer::T_M, 'M', 14],
                ],
            ],
            '35' => [
                'value' => '35',
                'expected' => [
                    [Lexer::T_INTEGER, 35, 0],
                ],
            ],
            '-25' => [
                'value' => '-25',
                'expected' => [
                    [Lexer::T_INTEGER, -25, 0],
                ],
            ],
            '-120.33' => [
                'value' => '-120.33',
                'expected' => [
                    [Lexer::T_FLOAT, -120.33, 0],
                ],
            ],
            'SRID' => [
                'value' => 'SRID',
                'expected' => [
                    [Lexer::T_SRID, 'SRID', 0],
                ],
            ],
            'SRID=4326;LINESTRING(0 0.0, 10.1 -10.025, 20.5 25.9, 53E-003 60)' => [
                'value' => 'SRID=4326;LINESTRING(0 0.0, 10.1 -10.025, 20.5 25.9, 53E-003 60)',
                'expected' => [
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
            ],
        ];
    }

    /**
     * @dataProvider tokenData
     */
    public function testTokenRecognition($value, array $expected)
    {
        $lexer = new Lexer($value);

        foreach ($expected as $token) {
            $lexer->moveNext();

            $actual = $lexer->lookahead;

            $this->assertEquals($token[0], $actual['type']);
            $this->assertEquals($token[1], $actual['value']);
            $this->assertEquals($token[2], $actual['position']);
        }
    }

    public function testTokenRecognitionReuseLexer()
    {
        $lexer = new Lexer();

        foreach (self::tokenData() as $name => $testData) {
            $lexer->setInput($testData['value']);

            foreach ($testData['expected'] as $token) {
                $lexer->moveNext();

                $actual = $lexer->lookahead;

                $this->assertEquals($token[0], $actual['type']);
                $this->assertEquals($token[1], $actual['value']);
                $this->assertEquals($token[2], $actual['position']);
            }
        }
    }
}
