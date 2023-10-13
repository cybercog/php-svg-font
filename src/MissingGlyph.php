<?php

declare(strict_types=1);

namespace Cog\SvgFont;

final class MissingGlyph
{
    public function __construct(
        public readonly int | null $horizontalAdvance = null,
    ) {
    }
}
