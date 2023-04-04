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

use LongitudeOne\Geo\WKT\Exception\ExceptionInterface;
use LongitudeOne\Geo\WKT\Exception\UnexpectedValueException;
use LongitudeOne\Geo\WKT\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Basic parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class ParserTest extends TestCase
{
    /**
     * @param string                   $value
     * @param array|ExceptionInterface $expected
     *
     * @dataProvider parserTestData
     */
    public function testParser($value, $expected)
    {
        $parser = new Parser($value);

        if ($expected instanceof ExceptionInterface) {
            $this->expectException(get_class($expected));
            $this->expectExceptionMessage($expected->getMessage());
        }

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    public function testReusedParser()
    {
        $parser = new Parser();

        foreach (self::parserTestData() as $name => $testData) {
            $value = $testData['value'];
            $expected = $testData['expected'];

            if ($expected instanceof ExceptionInterface) {
                $this->expectException(get_class($expected));
                $this->expectExceptionMessage($expected->getMessage());
            }

            $actual = $parser->parse($value);

            $this->assertEquals($expected, $actual, 'Failed dataset "' . $name . '"');
        }
    }

    /**
     * @return array[]
     */
    public static function parserTestData()
    {
        return [
            'testParsingGarbage' => [
                'value' => '@#_$%',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 0: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_TYPE, got "@" in value "@#_$%"'
                ),
            ],
            'testParsingBadType' => [
                'value' => 'PNT(10 10)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 0: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "PNT(10 10)"'
                ),
            ],
            'testParsingPointValue' => [
                'value' => 'POINT(34.23 -87)',
                'expected' => [
                    'srid' => null,
                    'type' => 'POINT',
                    'value' => [34.23, -87],
                    'dimension' => null,
                ],
            ],
            'testParsingPointZValue' => [
                'value' => 'POINT(34.23 -87 10)',
                'expected' => [
                    'srid' => null,
                    'type' => 'POINT',
                    'value' => [34.23, -87, 10],
                    'dimension' => 'Z',
                ],
            ],
            'testParsingPointDeclaredZValue' => [
                'value' => 'POINTZ(34.23 -87 10)',
                'expected' => [
                    'srid' => null,
                    'type' => 'POINT',
                    'value' => [34.23, -87, 10],
                    'dimension' => 'Z',
                ],
            ],
            'testParsingPointMValue' => [
                'value' => 'POINTM(34.23 -87 10)',
                'expected' => [
                    'srid' => null,
                    'type' => 'POINT',
                    'value' => [34.23, -87, 10],
                    'dimension' => 'M',
                ],
            ],
            'testParsingPointZMValue' => [
                'value' => 'POINT(34.23 -87 10 30)',
                'expected' => [
                    'srid' => null,
                    'type' => 'POINT',
                    'value' => [34.23, -87, 10, 30],
                    'dimension' => 'ZM',
                ],
            ],
            'testParsingPointDeclaredZMValue' => [
                'value' => 'POINT ZM(34.23 -87 10 30)',
                'expected' => [
                    'srid' => null,
                    'type' => 'POINT',
                    'value' => [34.23, -87, 10, 30],
                    'dimension' => 'ZM',
                ],
            ],
            'testParsingPointValueWithSrid' => [
                'value' => 'SRID=4326;POINT(34.23 -87)',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'POINT',
                    'value' => [34.23, -87],
                    'dimension' => null,
                ],
            ],
            'testParsingPointZValueWithSrid' => [
                'value' => 'SRID=4326;POINT(34.23 -87 10)',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'POINT',
                    'value' => [34.23, -87, 10],
                    'dimension' => 'Z',
                ],
            ],
            'testParsingPointValueScientificWithSrid' => [
                'value' => 'SRID=4326;POINT(4.23e-005 -8E-003)',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'POINT',
                    'value' => [0.0000423, -0.008],
                    'dimension' => null,
                ],
            ],
            'testParsingPointValueWithBadSrid' => [
                'value' => 'SRID=432.6;POINT(34.23 -87)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 5: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "432.6" in value "SRID=432.6;POINT(34.23 -87)"'
                ),
            ],
            'testParsingPointValueMissingCoordinate' => [
                'value' => 'POINT(34.23)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 11: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINT(34.23)"'
                ),
            ],
            'testParsingPointMValueMissingCoordinate' => [
                'value' => 'POINTM(34.23 10)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 15: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINTM(34.23 10)"'
                ),
            ],
            'testParsingPointMValueExtraCoordinate' => [
                'value' => 'POINTM(34.23 10 30 40)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "40" in value "POINTM(34.23 10 30 40)"'
                ),
            ],
            'testParsingPointZMValueMissingCoordinate' => [
                'value' => 'POINTZM(34.23 10 45)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINTZM(34.23 10 45)"'
                ),
            ],
            'testParsingPointZMValueExtraCoordinate' => [
                'value' => 'POINTZM(34.23 10 45 4.5 99)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 24: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "99" in value "POINTZM(34.23 10 45 4.5 99)"'
                ),
            ],
            'testParsingPointValueShortString' => [
                'value' => 'POINT(34.23',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col -1: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got end of string. in value "POINT(34.23"'
                ),
            ],
            'testParsingPointValueWrongScientificWithSrid' => [
                'value' => 'SRID=4326;POINT(4.23test-005 -8e-003)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 20: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "test" in value "SRID=4326;POINT(4.23test-005 -8e-003)"'
                ),
            ],
            'testParsingPointValueWithComma' => [
                'value' => 'POINT(10, 10)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 8: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "," in value "POINT(10, 10)"'
                ),
            ],
            'testParsingLineStringValue' => [
                'value' => 'LINESTRING(34.23 -87, 45.3 -92)',
                'expected' => [
                    'srid' => null,
                    'type' => 'LINESTRING',
                    'value' => [
                        [34.23, -87],
                        [45.3, -92],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingLineStringZValue' => [
                'value' => 'LINESTRING(34.23 -87 10, 45.3 -92 10)',
                'expected' => [
                    'srid' => null,
                    'type' => 'LINESTRING',
                    'value' => [
                        [34.23, -87, 10],
                        [45.3, -92, 10],
                    ],
                    'dimension' => 'Z',
                ],
            ],
            'testParsingLineStringMValue' => [
                'value' => 'LINESTRINGM(34.23 -87 10, 45.3 -92 10)',
                'expected' => [
                    'srid' => null,
                    'type' => 'LINESTRING',
                    'value' => [
                        [34.23, -87, 10],
                        [45.3, -92, 10],
                    ],
                    'dimension' => 'M',
                ],
            ],
            'testParsingLineStringZMValue' => [
                'value' => 'LINESTRINGZM(34.23 -87 10 20, 45.3 -92 10 20)',
                'expected' => [
                    'srid' => null,
                    'type' => 'LINESTRING',
                    'value' => [
                        [34.23, -87, 10, 20],
                        [45.3, -92, 10, 20],
                    ],
                    'dimension' => 'ZM',
                ],
            ],
            'testParsingLineStringValueWithSrid' => [
                'value' => 'SRID=4326;LINESTRING(34.23 -87, 45.3 -92)',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'LINESTRING',
                    'value' => [
                        [34.23, -87],
                        [45.3, -92],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingLineStringValueMissingCoordinate' => [
                'value' => 'LINESTRING(34.23 -87, 45.3)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 26: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "LINESTRING(34.23 -87, 45.3)"'
                ),
            ],
            'testParsingLineStringValueMismatchedDimensions' => [
                'value' => 'LINESTRING(34.23 -87, 45.3 56 23.4)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 30: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "23.4" in value "LINESTRING(34.23 -87, 45.3 56 23.4)"'
                ),
            ],
            'testParsingPolygonValue' => [
                'value' => 'POLYGON((0 0,10 0,10 10,0 10,0 0))',
                'expected' => [
                    'srid' => null,
                    'type' => 'POLYGON',
                    'value' => [
                        [
                            [0, 0],
                            [10, 0],
                            [10, 10],
                            [0, 10],
                            [0, 0],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingPolygonZValue' => [
                'value' => 'POLYGON((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0))',
                'expected' => [
                    'srid' => null,
                    'type' => 'POLYGON',
                    'value' => [
                        [
                            [0, 0, 0],
                            [10, 0, 0],
                            [10, 10, 0],
                            [0, 10, 0],
                            [0, 0, 0],
                        ],
                    ],
                    'dimension' => 'Z',
                ],
            ],
            'testParsingPolygonMValue' => [
                'value' => 'POLYGONM((0 0 0,10 0 0,10 10 0,0 10 0,0 0 0))',
                'expected' => [
                    'srid' => null,
                    'type' => 'POLYGON',
                    'value' => [
                        [
                            [0, 0, 0],
                            [10, 0, 0],
                            [10, 10, 0],
                            [0, 10, 0],
                            [0, 0, 0],
                        ],
                    ],
                    'dimension' => 'M',
                ],
            ],
            'testParsingPolygonZMValue' => [
                'value' => 'POLYGONZM((0 0 0 1,10 0 0 1,10 10 0 1,0 10 0 1,0 0 0 1))',
                'expected' => [
                    'srid' => null,
                    'type' => 'POLYGON',
                    'value' => [
                        [
                            [0, 0, 0, 1],
                            [10, 0, 0, 1],
                            [10, 10, 0, 1],
                            [0, 10, 0, 1],
                            [0, 0, 0, 1],
                        ],
                    ],
                    'dimension' => 'ZM',
                ],
            ],
            'testParsingPolygonValueWithSrid' => [
                'value' => 'SRID=4326;POLYGON((0 0,10 0,10 10,0 10,0 0))',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'POLYGON',
                    'value' => [
                        [
                            [0, 0],
                            [10, 0],
                            [10, 10],
                            [0, 10],
                            [0, 0],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingPolygonValueMultiRing' => [
                'value' => 'POLYGON((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5))',
                'expected' => [
                    'srid' => null,
                    'type' => 'POLYGON',
                    'value' => [
                        [
                            [0, 0],
                            [10, 0],
                            [10, 10],
                            [0, 10],
                            [0, 0],
                        ],
                        [
                            [5, 5],
                            [7, 5],
                            [7, 7],
                            [5, 7],
                            [5, 5],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingPolygonValueMultiRingWithSrid' => [
                'value' => 'SRID=4326;POLYGON((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5))',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'POLYGON',
                    'value' => [
                        [
                            [0, 0],
                            [10, 0],
                            [10, 10],
                            [0, 10],
                            [0, 0],
                        ],
                        [
                            [5, 5],
                            [7, 5],
                            [7, 7],
                            [5, 7],
                            [5, 5],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingPolygonValueMissingParenthesis' => [
                'value' => 'POLYGON(0 0,10 0,10 10,0 10,0 0)',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 8: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "0" in value "POLYGON(0 0,10 0,10 10,0 10,0 0)"'
                ),
            ],
            'testParsingPolygonValueMismatchedDimension' => [
                'value' => 'POLYGON((0 0,10 0,10 10 10,0 10,0 0))',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 24: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "10" in value "POLYGON((0 0,10 0,10 10 10,0 10,0 0))"'
                ),
            ],
            'testParsingPolygonValueMultiRingMissingComma' => [
                'value' => 'POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 33: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))"'
                ),
            ],
            'testParsingMultiPointValue' => [
                'value' => 'MULTIPOINT(0 0,10 0,10 10,0 10)',
                'expected' => [
                    'srid' => null,
                    'type' => 'MULTIPOINT',
                    'value' => [
                        [0, 0],
                        [10, 0],
                        [10, 10],
                        [0, 10],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingMultiPointMValue' => [
                'value' => 'MULTIPOINTM(0 0 0,10 0 0,10 10 0,0 10 0)',
                'expected' => [
                    'srid' => null,
                    'type' => 'MULTIPOINT',
                    'value' => [
                        [0, 0, 0],
                        [10, 0, 0],
                        [10, 10, 0],
                        [0, 10, 0],
                    ],
                    'dimension' => 'M',
                ],
            ],
            'testParsingMultiPointValueWithSrid' => [
                'value' => 'SRID=4326;MULTIPOINT(0 0,10 0,10 10,0 10)',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'MULTIPOINT',
                    'value' => [
                        [0, 0],
                        [10, 0],
                        [10, 10],
                        [0, 10],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingMultiPointValueWithExtraParenthesis' => [
                'value' => 'MULTIPOINT((0 0,10 0,10 10,0 10))',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 11: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "(" in value "MULTIPOINT((0 0,10 0,10 10,0 10))"'
                ),
            ],
            'testParsingMultiLineStringValue' => [
                'value' => 'MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))',
                'expected' => [
                    'srid' => null,
                    'type' => 'MULTILINESTRING',
                    'value' => [
                        [
                            [0, 0],
                            [10, 0],
                            [10, 10],
                            [0, 10],
                        ],
                        [
                            [5, 5],
                            [7, 5],
                            [7, 7],
                            [5, 7],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingMultiLineStringZValue' => [
                'value' => 'MULTILINESTRING((0 0 0,10 0 0,10 10 0,0 10 0),(5 5 1,7 5 1,7 7 1,5 7 1))',
                'expected' => [
                    'srid' => null,
                    'type' => 'MULTILINESTRING',
                    'value' => [
                        [
                            [0, 0, 0],
                            [10, 0, 0],
                            [10, 10, 0],
                            [0, 10, 0],
                        ],
                        [
                            [5, 5, 1],
                            [7, 5, 1],
                            [7, 7, 1],
                            [5, 7, 1],
                        ],
                    ],
                    'dimension' => 'Z',
                ],
            ],
            'testParsingMultiLineStringValueWithSrid' => [
                'value' => 'SRID=4326;MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'MULTILINESTRING',
                    'value' => [
                        [
                            [0, 0],
                            [10, 0],
                            [10, 10],
                            [0, 10],
                        ],
                        [
                            [5, 5],
                            [7, 5],
                            [7, 7],
                            [5, 7],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingMultiLineStringValueMissingComma' => [
                'value' => 'MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 37: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))"'
                ),
            ],
            'testParsingMultiPolygonValue' => [
                'value' => 'MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))',
                'expected' => [
                    'srid' => null,
                    'type' => 'MULTIPOLYGON',
                    'value' => [
                        [
                            [
                                [0, 0],
                                [10, 0],
                                [10, 10],
                                [0, 10],
                                [0, 0],
                            ],
                            [
                                [5, 5],
                                [7, 5],
                                [7, 7],
                                [5, 7],
                                [5, 5],
                            ],
                        ],
                        [
                            [
                                [1, 1],
                                [3, 1],
                                [3, 3],
                                [1, 3],
                                [1, 1],
                            ],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingMultiPolygonValueWithSrid' => [
                'value' => 'SRID=4326;MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'MULTIPOLYGON',
                    'value' => [
                        [
                            [
                                [0, 0],
                                [10, 0],
                                [10, 10],
                                [0, 10],
                                [0, 0],
                            ],
                            [
                                [5, 5],
                                [7, 5],
                                [7, 7],
                                [5, 7],
                                [5, 5],
                            ],
                        ],
                        [
                            [
                                [1, 1],
                                [3, 1],
                                [3, 3],
                                [1, 3],
                                [1, 1],
                            ],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingMultiPolygonValueMissingParenthesis' => [
                'value' => 'MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 64: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "1" in value "MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))"'
                ),
            ],
            'testParsingGeometryCollectionValue' => [
                'value' => 'GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))',
                'expected' => [
                    'srid' => null,
                    'type' => 'GEOMETRYCOLLECTION',
                    'value' => [
                        [
                            'type' => 'POINT',
                            'value' => [10, 10],
                        ],
                        [
                            'type' => 'POINT',
                            'value' => [30, 30],
                        ],
                        [
                            'type' => 'LINESTRING',
                            'value' => [
                                [15, 15],
                                [20, 20],
                            ],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingGeometryCollectionMValue' => [
                'value' => 'GEOMETRYCOLLECTIONM(POINT(10 10 0), POINT(30 30 0), LINESTRING(15 15 0, 20 20 0))',
                'expected' => [
                    'srid' => null,
                    'type' => 'GEOMETRYCOLLECTION',
                    'value' => [
                        [
                            'type' => 'POINT',
                            'value' => [10, 10, 0],
                        ],
                        [
                            'type' => 'POINT',
                            'value' => [30, 30, 0],
                        ],
                        [
                            'type' => 'LINESTRING',
                            'value' => [
                                [15, 15, 0],
                                [20, 20, 0],
                            ],
                        ],
                    ],
                    'dimension' => 'M',
                ],
            ],
            'testParsingGeometryCollectionValueWithSrid' => [
                'value' => 'SRID=4326;GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))',
                'expected' => [
                    'srid' => 4326,
                    'type' => 'GEOMETRYCOLLECTION',
                    'value' => [
                        [
                            'type' => 'POINT',
                            'value' => [10, 10],
                        ],
                        [
                            'type' => 'POINT',
                            'value' => [30, 30],
                        ],
                        [
                            'type' => 'LINESTRING',
                            'value' => [
                                [15, 15],
                                [20, 20],
                            ],
                        ],
                    ],
                    'dimension' => null,
                ],
            ],
            'testParsingGeometryCollectionValueWithBadType' => [
                'value' => 'GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))"'
                ),
            ],
            'testParsingGeometryCollectionValueWithMismatchedDimenstion' => [
                'value' => 'GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30 10), LINESTRING(15 15, 20 20))',
                'expected' => new UnexpectedValueException(
                    '[Syntax Error] line 0, col 45: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "10" in value "GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30 10), LINESTRING(15 15, 20 20))"'
                ),
            ],
        ];
    }
}
