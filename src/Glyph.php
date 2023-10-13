<?php

declare(strict_types=1);

namespace Cog\SvgFont;

use Cog\Unicode\Character;

final class Glyph
{
    public function __construct(
        public readonly Character $character,
        public readonly string | null $name = null,
        public readonly int | null $horizontalAdvance = null,
    ) {
        if ($horizontalAdvance !== null && $horizontalAdvance < 0) {
            throw new \InvalidArgumentException(
                "Glyph with unicode `$character` has negative horizontal advance",
            );
        }
    }
}
