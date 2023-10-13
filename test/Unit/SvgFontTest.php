<?php

declare(strict_types=1);

namespace Test\Unit\Cog\SvgFont;

use Cog\SvgFont\Font;
use Cog\SvgFont\FontList;
use Cog\Unicode\UnicodeString;
use PHPUnit\Framework\TestCase;

final class SvgFontTest extends TestCase
{
    /** @dataProvider provideItCanComputeStringWidth */
    public function testItCanComputeStringWidth(
        float $expectedWidth,
        string $string,
        int $fontSize,
        float $letterSpacing,
    ): void {
        $font = $this->getFontById('Bagnard');

        $this->assertSame(
            $expectedWidth,
            $font->computeStringWidth(
                UnicodeString::of($string),
                $fontSize,
                $letterSpacing,
            ),
        );
    }

    public static function provideItCanComputeStringWidth(): array
    {
        return [
            [0, 'Zero-width', 0, 0.0],
            [8.192, 'a', 16, 0.0],
            [9.712, 'b', 16, 0.0],
            [9.44, '4', 16, 0.0],
            [4.816, '.', 16, 0.0],
            [4.816, ',', 16, 0.0],
            [41.072, 'Hello', 16, 0.0],
            [43.344, 'world', 16, 0.0],
            [82.144, 'Hello', 32, 0.0],
            [86.688, 'world', 32, 0.0],
        ];
    }

    protected function getFontById(
        string $fontId,
    ): Font {
        $fonts = [
            'Bagnard' => 'BagnardSans.svg',
        ];

        $fontFileName = $fonts[$fontId] ?? null;

        if ($fontFileName === null) {
            throw new \DomainException("Unknown test case font id `$fontId`");
        }

        return FontList::ofFile(__DIR__ . '/../resource/' . $fontFileName)
            ->getById($fontId);
    }
}
