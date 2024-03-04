<?php
/**
 * Copyright (C) 2016 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace LongitudeOne\Geo\WKT\Tests;

use LongitudeOne\Geo\WKT\Lexer;
use PHPUnit\Framework\TestCase;

/**
 * Lexer tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class LexerTest extends TestCase
{
    /**
     * @param       $value
     * @param array $expected
     *
     * @dataProvider tokenData
     */
    public function testTokenRecognition($value, array $expected)
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

    public function testTokenRecognitionReuseLexer()
    {
        $lexer = new Lexer();

        foreach (self::tokenData() as $name => $testData) {
            $lexer->setInput($testData['value']);

            foreach ($testData['expected'] as $token) {
                $lexer->moveNext();

                $actual = $lexer->lookahead;

                $this->assertEquals($token[0], $actual->type);
                $this->assertEquals($token[1], $actual->value);
                $this->assertEquals($token[2], $actual->position);
            }
        }
    }

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
}
