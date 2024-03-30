# Well-known text parser: longitude-one/wkt-parser

![longitude-one/wkt-parser](https://img.shields.io/badge/longitude--one-wkt--parser-blue)
![Stable release](https://img.shields.io/github/v/release/longitude-one/wkt-parser)
[![Packagist License](https://img.shields.io/packagist/l/longitude-one/wkt-parser)](https://github.com/longitude-one/wkt-parser/blob/main/LICENSE)

Lexer and parser library for 2D, 3D, and 4D WKT/EWKT spatial object strings.

[![PHP CI](https://github.com/longitude-one/wkt-parser/actions/workflows/ci.yml/badge.svg)](https://github.com/longitude-one/wkt-parser/actions/workflows/ci.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/9f5fc3374175f36bb142/maintainability)](https://codeclimate.com/github/longitude-one/wkt-parser/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/9f5fc3374175f36bb142/test_coverage)](https://codeclimate.com/github/longitude-one/wkt-parser/test_coverage)
![Minimum PHP Version](https://img.shields.io/packagist/php-v/longitude-one/wkt-parser.svg?maxAge=3600)


[![CI](https://github.com/longitude-one/wkt-parser/actions/workflows/ci.yml/badge.svg)](https://github.com/longitude-one/wkt-parser/actions/workflows/ci.yml)
[![Downloads](https://img.shields.io/packagist/dm/longitude-one/wkt-parser.svg)](https://packagist.org/packages/longitude-one/wkt-parser)

> [!NOTE]
> This package is the continuation of the now abandoned [creof/wkt-parser](https://github.com/creof/wkt-parser) package.

## Installation

```bash
composer require longitude-one/wkt-parser
```

## Usage

There are two use patterns for the parser. The value to be parsed can be passed into the constructor, then parse()
called on the returned ```Parser``` object:

```php
$input = 'POLYGON((0 0,10 0,10 10,0 10,0 0))';

$parser = new Parser($input);

$value = $parser->parse();
```

If many values need to be parsed, a single ```Parser``` instance can be used:

```php
$input1 = 'POLYGON((0 0,10 0,10 10,0 10,0 0))';
$input2 = 'POINT(0,0)';

$parser = new Parser();

$value1 = $parser->parse($input1);
$value2 = $parser->parse($input2);
```

## Return

The parser will return an array with the keys ```type```, ```value```, ```srid```, and ```dimension```.
- ```type``` string, the spatial object type (POINT, LINESTRING, etc.) without any dimension.
- ```value``` array, contains integer or float values for points, or nested arrays containing these based on spatial object type.
- ```srid``` integer, the SRID if EWKT value was parsed, ```null``` otherwise.
- ```dimension``` string, will contain ```Z```, ```M```, or ```ZM``` for the respective 3D and 4D objects, ```null``` otherwise.

## Exceptions

The ```Lexer``` and ```Parser``` will throw exceptions implementing interface ```LongitudeOne\Geo\WKT\Exception\ExceptionInterface```.
