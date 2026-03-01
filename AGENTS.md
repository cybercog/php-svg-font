# AGENTS.md

This file provides guidance to LLM Agents when working with code in this repository.

## Project

PHP SVG font parser and text measurement library (`cybercog/php-svg-font`). Parses SVG font files and computes text width metrics using glyph horizontal advance values, units-per-em, font size, and letter spacing.

## Commands

All commands must be run inside Docker containers (PHP is not installed on the host).

```bash
# Install dependencies
docker compose run php84 composer install

# Run all tests
docker compose run php84 vendor/bin/phpunit

# Run a single test
docker compose run php84 vendor/bin/phpunit --filter testItCanComputeStringWidth

# Run tests with verbose output (matches CI)
docker compose run php84 vendor/bin/phpunit --testdox

# Run tests against a specific PHP version (services: php81, php82, php83, php84, php85)
docker compose run php85 vendor/bin/phpunit
```

## Architecture

Entry point is `FontList::ofFile()` which parses an SVG font file and returns a `FontList`. Use `FontList::getById()` to get a `Font`, then `Font::computeStringWidth()` for text measurement.

**Parsing pipeline:** `FontList::ofFile()` → `SimpleXmlSvgFontFileParser::parseFile()` → XPath queries on `//svg:defs/svg:font` elements → constructs `Font` objects with `FontFace`, `MissingGlyph`, and glyph map (`Character` → `Glyph`).

**Parser extensibility:** `SvgFontFileParserInterface` allows alternative parser implementations. The default `SimpleXmlSvgFontFileParser` requires the `ext-simplexml` PHP extension.

**Key dependency:** `cybercog/php-unicode` provides `Character` and `UnicodeString` types used throughout for Unicode-safe text handling.

## Code Conventions

- All domain classes are `final` with `readonly` public properties (immutable value objects)
- `declare(strict_types=1)` in every file
- PSR-4 autoloading: `Cog\SvgFont\` → `src/`, `Test\Unit\Cog\SvgFont\` → `test/`
- 4 spaces indentation, LF line endings
- Tests use `@dataProvider` for data-driven testing
- Test font fixture: `test/resource/BagnardSans.svg`
