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

use LongitudeOne\Geo\WKT\Exception\NotExistentException;
use LongitudeOne\Geo\WKT\Exception\NotInstantiableException;
use LongitudeOne\Geo\WKT\Exception\UnexpectedValueException;
use LongitudeOne\Geo\WKT\Parser;
use LongitudeOne\Geo\WKT\Tests\Utils\SpecificTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Basic parser tests.
 */
class ParserTest extends SpecificTestCase
{
    /**
     * @return \Generator<string, array{0: string, 1: string}, null, void>
     */
    public static function notExistentValuesProvider(): \Generator
    {
        yield 'testParsingGarbage' => ['@#_$%', 'According the ISO 13249-3:2016 standard, the "@" type does not exist.'];
        yield 'testParsingBadType' => ['PNT(10 10)', 'According the ISO 13249-3:2016 standard, the "PNT" type does not exist.'];
        yield 'testParsingGeometryCollectionValueWithBadType' => ['GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))', 'According the ISO 13249-3:2016 standard, the "PNT" type does not exist.'];
    }

    /**
     * @return \Generator<string, array{0: string, 1:string}, null, void>
     */
    public static function notInstantiableTypesProvider(): \Generator
    {
        yield 'testNotInstantiableGeometry' => [Parser::GEOMETRY, 'According the ISO 13249-3:2016 standard, the "GEOMETRY" type is not instantiable. Did you mean "GEOMETRYCOLLECTION"?'];
        yield 'testNotInstantiableCurve' => [Parser::CURVE, 'According the ISO 13249-3:2016 standard, the "CURVE" type is not instantiable. Did you mean "MULTICURVE"?'];
        yield 'testNotInstantiableSolid' => [Parser::SOLID, 'According the ISO 13249-3:2016 standard, the "SOLID" type is not instantiable. Did you mean "POLYGON"?'];
        yield 'testNotInstantiableSurface' => [Parser::SURFACE, 'According the ISO 13249-3:2016 standard, the "SURFACE" type is not instantiable. Did you mean "MULTISURFACE"?'];
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}, null, void>
     */
    public static function unexpectedValues(): \Generator
    {
        yield 'testParsingPointValueWithBadSrid' => ['SRID=432.6;POINT(34.23 -87)', '[Syntax Error] line 0, col 5: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "432.6" in value "SRID=432.6;POINT(34.23 -87)"'];
        yield 'testParsingPointValueMissingCoordinate' => ['POINT(34.23)', '[Syntax Error] line 0, col 11: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINT(34.23)"'];
        yield 'testParsingPointMValueMissingCoordinate' => ['POINTM(34.23 10)', '[Syntax Error] line 0, col 15: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINTM(34.23 10)"'];
        yield 'testParsingPointMValueExtraCoordinate' => ['POINTM(34.23 10 30 40)', '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "40" in value "POINTM(34.23 10 30 40)"'];
        yield 'testParsingPointZMValueMissingCoordinate' => ['POINTZM(34.23 10 45)', '[Syntax Error] line 0, col 19: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINTZM(34.23 10 45)"'];
        yield 'testParsingPointZMValueExtraCoordinate' => ['POINTZM(34.23 10 45 4.5 99)', '[Syntax Error] line 0, col 24: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "99" in value "POINTZM(34.23 10 45 4.5 99)"'];
        yield 'testParsingPointValueShortString' => ['POINT(34.23', '[Syntax Error] line 0, col -1: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got end of string. in value "POINT(34.23"'];
        yield 'testParsingPointValueWrongScientificWithSrid' => ['SRID=4326;POINT(4.23test-005 -8e-003)', '[Syntax Error] line 0, col 20: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "test" in value "SRID=4326;POINT(4.23test-005 -8e-003)"'];
        yield 'testParsingPointValueWithComma' => ['POINT(10, 10)', '[Syntax Error] line 0, col 8: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got "," in value "POINT(10, 10)"'];
        yield 'testParsingLineStringValueMissingCoordinate' => ['LINESTRING(34.23 -87, 45.3)', '[Syntax Error] line 0, col 26: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_INTEGER, got ")" in value "LINESTRING(34.23 -87, 45.3)"'];
        yield 'testParsingLineStringValueMismatchedDimensions' => ['LINESTRING(34.23 -87, 45.3 56 23.4)', '[Syntax Error] line 0, col 30: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "23.4" in value "LINESTRING(34.23 -87, 45.3 56 23.4)"'];
        yield 'testParsingPolygonValueMissingParenthesis' => ['POLYGON(0 0,10 0,10 10,0 10,0 0)', '[Syntax Error] line 0, col 8: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "0" in value "POLYGON(0 0,10 0,10 10,0 10,0 0)"'];
        yield 'testParsingPolygonValueMismatchedDimension' => ['POLYGON((0 0,10 0,10 10 10,0 10,0 0))', '[Syntax Error] line 0, col 24: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "10" in value "POLYGON((0 0,10 0,10 10 10,0 10,0 0))"'];
        yield 'testParsingPolygonValueMultiRingMissingComma' => ['POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))', '[Syntax Error] line 0, col 33: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))"'];
        yield 'testParsingMultiLineStringValueMissingComma' => ['MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))', '[Syntax Error] line 0, col 37: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))"'];
        yield 'testParsingMultiPolygonValueMissingParenthesis' => ['MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))', '[Syntax Error] line 0, col 64: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "1" in value "MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))"'];
        yield 'testParsingGeometryCollectionValueWithMismatchedDimension' => ['GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30 10), LINESTRING(15 15, 20 20))', '[Syntax Error] line 0, col 45: Error: Expected LongitudeOne\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "10" in value "GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30 10), LINESTRING(15 15, 20 20))"'];
    }

    #[DataProvider('notInstantiableTypesProvider')]
    public function testNotInstantiable(string $notInstantiableType, string $expectedMessage): void
    {
        self::expectException(NotInstantiableException::class);
        self::expectExceptionMessage($expectedMessage);
        $parser = new Parser();
        $parser->parse($notInstantiableType.'(10 10)');
    }

    public function testNullParser(): void
    {
        $parser = new Parser();
        self::expectException(UnexpectedValueException::class);
        self::expectExceptionMessage('No value provided');
        $parser->parse();
    }

    #[DataProvider('unexpectedValues')]
    public function testParserWithUnexpectedValues(string $value, string $exceptionMessage): void
    {
        $parser = new Parser($value);

        self::expectException(UnexpectedValueException::class);
        self::expectExceptionMessage($exceptionMessage);

        $parser->parse();
    }

    #[DataProvider('notExistentValuesProvider')]
    public function testParsingGarbage(string $garbage, string $message): void
    {
        $parser = new Parser($garbage);
        self::expectException(NotExistentException::class);
        self::expectExceptionMessage($message);
        $parser->parse();
    }
}
