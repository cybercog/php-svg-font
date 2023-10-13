<?php

declare(strict_types=1);

namespace Cog\SvgFont\Parser;

use Cog\SvgFont\Font;

interface SvgFontFileParserInterface
{
    /**
     * Takes local path to SVG font file and processes its XML
     * to get every character parameters.
     *
     * @return list<Font>
     */
    public function parseFile(
        string $filePath,
    ): array;
}
