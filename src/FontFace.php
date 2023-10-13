<?php

declare(strict_types=1);

namespace Cog\SvgFont;

final class FontFace
{
    private const DEFAULT_UNITS_PER_EM = 1000;

    public function __construct(
        public readonly int | null $unitsPerEm = self::DEFAULT_UNITS_PER_EM,
    ) {
    }
}
