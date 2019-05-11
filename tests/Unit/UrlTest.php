<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Client\Response\Elements\Url;

class UrlTest extends TestCase
{
    public function testRelativeProtocolBaseUrl(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('First URL must be absolute');

        $url = new Url('//example.com/test');
        $url->getUrl();
    }

    public function testRelativeBaseUrl(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('First URL must be absolute');

        $url = new Url('/test');
        $url->getUrl();
    }

    public function testUnsupportedProtocol(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Only http and https protocols are supported');

        $url = new Url('file:///C:/Users/User/file.html');
        $url->getUrl();
    }

    /**
     * @param $url string URL being tested
     * @param $currentUrl string Current URL context
     * @param $expectedUrl string Expected final URL
     * @dataProvider urlsProvider
     */
    public function testUrlResolving($url, $currentUrl, $expectedUrl): void
    {
        $url = new Url($url, $currentUrl);

        $this->assertEquals($expectedUrl, $url->getUrl());
    }

    /**
     * @param $url
     * @param $expectedDomain
     * @dataProvider domainsProvider
     */
    public function testExtractingUrlDomain($url, $expectedDomain): void
    {
        $url = new Url($url, 'http://example.com/irrelevant');

        $this->assertEquals($expectedDomain, $url->getDomain());
    }

    public function testCurrentUrlGetter(): void
    {
        $url = new Url('http://example.org/index.php?foo=bar', 'http://example.org/sitemap.xml');

        $this->assertEquals('http://example.org/sitemap.xml', $url->getCurrentUrl());
    }

    public function testMethodGetter(): void
    {
        $url = new Url('http://example.org/index.php?foo=bar', 'http://example.org/sitemap.xml');
        $url2 = new Url('http://example.net', null, 'POST');

        $this->assertEquals('GET', $url->getMethod());
        $this->assertEquals('POST', $url2->getMethod());
    }

    public function testRawUrlGetter(): void
    {
        $url = new Url('//example.org/index.php?foo=bar', 'http://example.org/sitemap.xml');

        $this->assertEquals('//example.org/index.php?foo=bar', $url->getRawUrl());
    }

    public function testCastingUrlToString(): void
    {
        $url = new Url('//example.org/index.php?foo=bar', 'http://example.org/sitemap.xml');

        $this->assertEquals('http://example.org/index.php?foo=bar', (string) $url);
    }

    public static function urlsProvider()
    {
        return [
            // [url, currentUrl, expectedUrl]
            ['http://example.com', 'http://example.com', 'http://example.com'],
            ['//example.com/test', 'https://example.com', 'https://example.com/test'],
            ['http://example.com/test#anchor', 'http://example.com/test2', 'http://example.com/test'],
            ['http://example.com/index.php?foo=bar&baz=test', null, 'http://example.com/index.php?foo=bar&baz=test'],

            ['?foo=bar', 'http://example.com/index.php', 'http://example.com/index.php?foo=bar'],
            ['?page=2', 'http://example.com/blog.php?page=1&id=3', 'http://example.com/blog.php?page=2'],

            ['/test', 'http://example.com', 'http://example.com/test'],
            ['/test', 'http://example.com/', 'http://example.com/test'],
            ['test', 'http://example.com', 'http://example.com/test'],
            ['test', 'http://example.com/', 'http://example.com/test'],

            ['', 'http://example.com/index.php?foo=bar', 'http://example.com/index.php?foo=bar'],

            ['test2', 'http://example.com/test', 'http://example.com/test2'],
        ];
    }

    public static function domainsProvider()
    {
        return [
            // [url, expectedDomain]
            ['http://example.com', 'http://example.com'],
            ['http://www.example.com/foo', 'http://www.example.com'],
            ['http://username:@example.com/foo', 'http://username:@example.com'],
            ['http://username:password@example.com/foo', 'http://username:password@example.com'],
            ['http://username:password@127.0.0.1:8080/index.php?args=test', 'http://username:password@127.0.0.1:8080'],
        ];
    }
}
