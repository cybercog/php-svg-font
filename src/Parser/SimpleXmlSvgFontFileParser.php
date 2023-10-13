<?php

declare(strict_types=1);

namespace Cog\SvgFont\Parser;

use Cog\SvgFont\Font;
use Cog\SvgFont\FontFace;
use Cog\SvgFont\Glyph;
use Cog\SvgFont\MissingGlyph;
use Cog\Unicode\Character;

final class SimpleXmlSvgFontFileParser implements
    SvgFontFileParserInterface
{
    private const ATTRIBUTE_ID = 'id';
    private const ATTRIBUTE_HORIZ_ADV_X = 'horiz-adv-x';
    private const ATTRIBUTE_UNITS_PER_EM = 'units-per-em';
    private const ATTRIBUTE_UNICODE = 'unicode';
    private const ATTRIBUTE_GLYPH_NAME = 'glyph-name';

    private const ELEMENT_NAME_FONT_FACE = 'font-face';
    private const ELEMENT_NAME_MISSING_GLYPH = 'missing-glyph';
    private const ELEMENT_NAME_GLYPH = 'glyph';

    public function __construct()
    {
        if (!extension_loaded('simplexml')) {
            throw new \LogicException('SimpleXML extension is not enabled.');
        }
    }

    /**
     * @inheritDoc
     */
    public function parseFile(
        string $filePath,
    ): array {
        $xml = simplexml_load_file($filePath);
        $xml->registerXPathNamespace('svg', 'http://www.w3.org/2000/svg');

        $fontList = [];

        $fontElements = $xml->xpath('//svg:defs/svg:font');

        foreach ($fontElements as $fontElement) {
            $fontList[] = $this->initFont($fontElement);
        }

        return $fontList;
    }

    private function initFont(
        \SimpleXMLElement $fontElement,
    ): Font {
        $fontId = strval($fontElement[self::ATTRIBUTE_ID]);
        $defaultHorizontalAdvance = intval($fontElement[self::ATTRIBUTE_HORIZ_ADV_X]);
        $glyphMap = [];

        foreach ($fontElement as $fontChildElement) {
            switch ($fontChildElement->getName()) {
                case self::ELEMENT_NAME_FONT_FACE:
                    $fontFace = $this->initFontFace($fontChildElement);
                    break;
                case self::ELEMENT_NAME_MISSING_GLYPH:
                    $missingGlyph = $this->initMissingGlyph($fontChildElement);
                    break;
                case self::ELEMENT_NAME_GLYPH:
                    $unicode = strval($fontChildElement[self::ATTRIBUTE_UNICODE]);

                    if ($unicode !== '') {
                        try {
                            $character = Character::of($unicode);
                            $glyphMap[$unicode] = $this->initGlyph($fontChildElement, $character);
                        } catch (\Exception $exception) {
                            // TODO: Add multiple character support
                        }
                    }
                    break;
            }
        }

        if (!isset($fontFace)) {
            throw new \DomainException(
                "SVG font with id `$fontId` missing `font-face` XML element",
            );
        }

        if (!isset($missingGlyph)) {
            throw new \DomainException(
                "SVG font with id `$fontId` missing `missing-glyph` XML element",
            );
        }

        return new Font(
            $fontId,
            $defaultHorizontalAdvance,
            $fontFace,
            $missingGlyph,
            $glyphMap,
        );
    }

    private function initFontFace(
        \SimpleXMLElement $fontFaceElement,
    ): FontFace {
        $unitsPerEm = intval($fontFaceElement[self::ATTRIBUTE_UNITS_PER_EM]);

        if ($unitsPerEm === 0) {
            $unitsPerEm = 1000;
        }

        return new FontFace(
            $unitsPerEm,
        );
    }

    private function initGlyph(
        \SimpleXMLElement $glyphElement,
        Character $character,
    ): Glyph {
        $name = strval($glyphElement[self::ATTRIBUTE_GLYPH_NAME]);

        if ($name === '') {
            $name = null;
        }

        $horizontalAdvance = intval($glyphElement[self::ATTRIBUTE_HORIZ_ADV_X]);

        if ($horizontalAdvance === 0) {
            $horizontalAdvance = null;
        }

        return new Glyph(
            $character,
            $name,
            $horizontalAdvance,
        );
    }

    private function initMissingGlyph(
        \SimpleXMLElement $missingGlyphElement,
    ): MissingGlyph {
        $horizontalAdvance = intval($missingGlyphElement[self::ATTRIBUTE_HORIZ_ADV_X]);

        if ($horizontalAdvance === 0) {
            $horizontalAdvance = null;
        }

        return new MissingGlyph(
            $horizontalAdvance,
        );
    }
}
