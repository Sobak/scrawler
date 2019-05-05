<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Symfony\Component\DomCrawler\Crawler;

class CssSelectorMatcherTest extends TestCase
{
    public function testMatchingHtmlByClass(): void
    {
        $matcher = new CssSelectorHtmlMatcher('span.match');
        $matcher->setCrawler(new Crawler(file_get_contents(__DIR__ . '/../Fixtures/interesting.html')));

        $this->assertEquals('interesting', $matcher->match());
    }
}
