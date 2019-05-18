<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Block\Matcher\XpathHtmlMatcher;
use Symfony\Component\DomCrawler\Crawler;

class XpathMatcherTest extends TestCase
{
    public function testMatchingHtmlByXpathClass(): void
    {
        $matcher = new XpathHtmlMatcher("//*[contains(@class,'match')]");
        $matcher->setCrawler(new Crawler(file_get_contents(__DIR__ . '/../Fixtures/interesting.html')));

        $this->assertEquals('<strong>very</strong> interesting', $matcher->match());
    }

    public function testMatchingHtmlByXpathNonExistentClass(): void
    {
        $matcher = new XpathHtmlMatcher("//*[contains(@class,'not-found')]");
        $matcher->setCrawler(new Crawler(file_get_contents(__DIR__ . '/../Fixtures/interesting.html')));

        $this->assertEquals(null, $matcher->match());
    }
}
