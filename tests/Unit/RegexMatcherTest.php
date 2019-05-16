<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Block\Matcher\RegexHtmlMatcher;
use Symfony\Component\DomCrawler\Crawler;

class RegexMatcherTest extends TestCase
{
    public function testMatchingHtmlByRegex(): void
    {
        $matcher = new RegexHtmlMatcher('#<span class="match">(?P<result>.+)</span>#m');
        $matcher->setCrawler(new Crawler(file_get_contents(__DIR__ . '/../Fixtures/interesting.html')));

        $this->assertEquals('<strong>very</strong> interesting', $matcher->match());
    }

    public function testMatchingHtmlByRegexWithNoGroup(): void
    {
        $matcher = new RegexHtmlMatcher('#<span class="match">.+</span>#m');
        $matcher->setCrawler(new Crawler(file_get_contents(__DIR__ . '/../Fixtures/interesting.html')));

        $this->assertEquals(null, $matcher->match());
    }
}
