<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Support\Utils;

class UtilsTest extends TestCase
{
    /**
     * @dataProvider slugifyTestDataProvider
     * @param string $string
     * @param string $slug
     * @param string $separator
     */
    public function testSlugifyHelper($string, $slug, $separator = '-'): void
    {
        $this->assertEquals($slug, Utils::slugify($string, $separator));
    }

    public function testSlugifyHelperWithNonAsciiOnly(): void
    {
        $this->assertEquals(13, strlen(Utils::slugify('żółć')));
    }

    public function testTrimWhitespaceHelper(): void
    {
        $this->assertEquals('test', Utils::trimWhitespace(" \t\n\rtest  \0\x0B"));
        $this->assertEquals('test', Utils::trimWhitespace(" test		"));
    }

    public static function slugifyTestDataProvider(): array
    {
        return [
            ['test-string', 'test-string'],
            ['test string', 'test-string'],
            ['sprawdź to', 'sprawd-to'],
            [' #cleanup @23', 'cleanup-23'],
            ['custom separator test', 'custom_separator_test', '_'],
        ];
    }
}
