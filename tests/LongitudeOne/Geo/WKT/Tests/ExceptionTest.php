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
use LongitudeOne\Geo\WKT\Exception\NotYetImplementedException;
use LongitudeOne\Geo\WKT\Exception\UnexpectedValueException;
use LongitudeOne\Geo\WKT\Parser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * @return \Generator<string, array{0: string, 1: string}, null, void>
     */
    public static function notInstantiableTypes(): \Generator
    {
        yield Parser::CURVE => [Parser::CURVE, 'According the ISO 13249-3:2016 standard, the "CURVE" type is not instantiable. Did you mean "MULTICURVE"?'];
        yield Parser::GEOMETRY => [Parser::GEOMETRY, 'According the ISO 13249-3:2016 standard, the "GEOMETRY" type is not instantiable. Did you mean "GEOMETRYCOLLECTION"?'];
        yield Parser::SOLID => [Parser::SOLID, 'According the ISO 13249-3:2016 standard, the "SOLID" type is not instantiable. Did you mean "POLYGON"?'];
        yield Parser::SURFACE => [Parser::SURFACE, 'According the ISO 13249-3:2016 standard, the "SURFACE" type is not instantiable. Did you mean "MULTISURFACE"?'];
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}, null, void>
     */
    public static function notYetImplementedTypes(): \Generator
    {
        yield Parser::BREP_SOLID => [Parser::BREP_SOLID, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "BREPSOLID".'];
        yield Parser::CIRCLE => [Parser::CIRCLE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "CIRCLE".'];
        yield Parser::CLOTHOID => [Parser::CLOTHOID, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "CLOTHOID".'];
        yield Parser::COMPOUND_CURVE => [Parser::COMPOUND_CURVE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "COMPOUNDCURVE".'];
        yield Parser::COMPOUND_SURFACE => [Parser::COMPOUND_SURFACE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "COMPOUNDSURFACE".'];
        yield Parser::CURVE_POLYGON => [Parser::CURVE_POLYGON, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "CURVEPOLYGON".'];
        yield Parser::ELLIPTICAL_CURVE => [Parser::ELLIPTICAL_CURVE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "ELLIPTICALCURVE".'];
        yield Parser::GEODESIC_STRING => [Parser::GEODESIC_STRING, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "GEODESICSTRING".'];
        yield Parser::MULTI_CURVE => [Parser::MULTI_CURVE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "MULTICURVE".'];
        yield Parser::MULTI_SURFACE => [Parser::MULTI_SURFACE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "MULTISURFACE".'];
        yield Parser::NURBS_CURVE => [Parser::NURBS_CURVE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "NURBSCURVE".'];
        yield Parser::SPIRAL_CURVE => [Parser::SPIRAL_CURVE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "SPIRALCURVE".'];
        yield Parser::POLYHEDRAL_SURFACE => [Parser::POLYHEDRAL_SURFACE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "POLYHDRLSURFACE".'];
        yield Parser::TRIANGLE => [Parser::TRIANGLE, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "TRIANGLE".'];
        yield Parser::TIN => [Parser::TIN, 'The LongitudeOne\Geo\WKT\Parser is not yet able to parse "TIN".'];
    }

    public function testNotExistentException(): void
    {
        $this->expectException(NotExistentException::class);
        $this->expectExceptionMessage('According the ISO 13249-3:2016 standard, the "FOO" type does not exist.');

        (new Parser('FOO(42 42)'))->parse();
    }

    #[DataProvider('notInstantiableTypes')]
    public function testNotInstantiable(string $notInstantiableType, string $expectedMessage): void
    {
        $this->expectException(NotInstantiableException::class);
        $this->expectExceptionMessage($expectedMessage);

        $toParse = sprintf('%s(42 42)', $notInstantiableType);

        (new Parser($toParse))->parse();
    }

    #[DataProvider('notYetImplementedTypes')]
    public function testNotYetImplemented(string $notYetImplemented, string $expectedMessage): void
    {
        $this->expectException(NotYetImplementedException::class);
        $this->expectExceptionMessage($expectedMessage);

        $toParse = sprintf('%s(42 42)', $notYetImplemented);

        (new Parser($toParse))->parse();
    }
}
