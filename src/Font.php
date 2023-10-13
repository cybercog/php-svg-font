<?php

declare(strict_types=1);

namespace Cog\SvgFont;

use Cog\Unicode\Character;
use Cog\Unicode\UnicodeString;

final class Font
{
    private const UNICODE_CODE_POINT_LINE_FEED = 10;

    /**
     * @param array<int, Glyph> $glyphMap
     */
    public function __construct(
        public readonly string $id,
        private readonly int $horizontalAdvance,
        private readonly FontFace $fontFace,
        private readonly MissingGlyph $missingGlyph,
        private readonly array $glyphMap = [],
    ) {
        if ($horizontalAdvance < 0) {
            throw new \InvalidArgumentException(
                "Font with id `$id` has negative horizontal advance",
            );
        }
    }

    public function computeStringWidth(
        UnicodeString $string,
        int $size,
        float $letterSpacing = 0.0,
    ): float {
        $maxLineWidth = $lineWidth = 0;

        $characterList = $string->characterList();

        foreach ($characterList as $character) {
            if ($character->toDecimal() === self::UNICODE_CODE_POINT_LINE_FEED) {
                $maxLineWidth = max($maxLineWidth, $lineWidth);
                $lineWidth = 0;
                continue;
            }

            $lineWidth += $this->computeCharacterWidth($character, $size, $letterSpacing);
        }

        return max($maxLineWidth, $lineWidth);
    }

    private function computeCharacterWidth(
        Character $character,
        int $size,
        float $letterSpacing = 0.0,
    ): float {
        $size = $size / $this->fontFace->unitsPerEm;

        $glyphHorizontalAdvance = $this->resolveGlyphHorizontalAdvance($character);

        $glyphWidth = $glyphHorizontalAdvance * $size;
        $letterSpacingWidth = $this->fontFace->unitsPerEm * $letterSpacing * $size;

        return $glyphWidth + $letterSpacingWidth;
    }

    private function resolveGlyphHorizontalAdvance(
        Character $character,
    ): int {
        return isset($this->glyphMap[strval($character)])
            ? $this->glyphMap[strval($character)]->horizontalAdvance ?? $this->horizontalAdvance
            : $this->missingGlyph->horizontalAdvance ?? $this->horizontalAdvance;
    }
}
