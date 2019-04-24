<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Client\Response\Elements\Url;

class UrlTest extends TestCase
{
    public function testRelativeProtocolBaseUrl(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('First URL must use explicit protocol (http/https)');

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

    public static function urlsProvider()
    {
        return [
            // [url, currentUrl, expectedUrl]
            ['http://example.com', 'http://example.com', 'http://example.com'],
            ['//example.com/test', 'https://example.com', 'https://example.com/test'],
            ['http://example.com/test#anchor', 'http://example.com/test2', 'http://example.com/test'],
            ['http://example.com/index.php?foo=bar&baz=test', null, 'http://example.com/index.php?foo=bar&baz=test'],
            ['?foo=bar', 'http://example.com/index.php', 'http://example.com/index.php?foo=bar'],

            ['/test', 'http://example.com', 'http://example.com/test'],
            ['/test', 'http://example.com/', 'http://example.com/test'],
            ['test', 'http://example.com', 'http://example.com/test'],
            ['test', 'http://example.com/', 'http://example.com/test'],
        ];
    }
}
