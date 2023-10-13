<?php

declare(strict_types=1);

namespace Cog\SvgFont;

use Cog\SvgFont\Parser\SimpleXmlSvgFontFileParser;
use Cog\SvgFont\Parser\SvgFontFileParserInterface;

final class FontList
{
    /**
     * @param list<Font> $fontList
     */
    private function __construct(
        private readonly array $fontList,
    ) {
    }

    /**
     * @param list<Font> $fontList
     */
    public static function of(
        array $fontList,
    ): self {
        foreach ($fontList as $font) {
            if (!($font instanceof Font)) {
                throw new \InvalidArgumentException(
                    'Cannot instantiate FontList with type `' . get_class($font) . '`'
                );
            }
        }

        return new self(
            $fontList,
        );
    }

    public static function ofFile(
        string $filePath,
        SvgFontFileParserInterface $fontFileParser = null,
    ): self {
        if ($fontFileParser === null) {
            $fontFileParser = new SimpleXmlSvgFontFileParser();
        }

        $fontList = $fontFileParser->parseFile(
            $filePath,
        );

        // TODO: Assert font list types

        return new self(
            $fontList,
        );
    }

    public function getById(
        string $id,
    ): Font {
        foreach ($this->fontList as $font) {
            if ($font->id === $id) {
                return $font;
            }
        }

        throw new \DomainException(
            "Cannot get unknown font with id `$id`",
        );
    }
}
