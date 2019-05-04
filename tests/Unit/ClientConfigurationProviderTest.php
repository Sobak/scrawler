<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Block\ClientConfigurationProvider\LiteralProvider;
use Sobak\Scrawler\Block\ClientConfigurationProvider\ScrawlerUserAgentProvider;
use Sobak\Scrawler\Client\ClientFactory;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Scrawler;

class ClientConfigurationProviderTest extends TestCase
{
    public function testScrawlerUserAgentProvider(): void
    {
        $scrawler = new Configuration();
        $scrawler->addClientConfigurationProvider(new ScrawlerUserAgentProvider('BotName', 'http://example.com'));

        $client = ClientFactory::buildInstance($scrawler->getClientConfigurationProviders());
        $userAgent = $client->getConfig()['headers']['User-Agent'];

        $this->assertStringContainsString('BotName', $userAgent);
        $this->assertStringContainsString('http://example.com', $userAgent);
        $this->assertStringContainsString('Scrawler ' . Scrawler::VERSION, $userAgent);
        $this->assertStringContainsString('http://scrawler.sobak.pl', $userAgent);
    }

    public function testLiteralConfigurationProvider(): void
    {
        $authConfig = [
            'user',
            'pass',
            'basic',
        ];

        $scrawler = new Configuration();
        $scrawler->addClientConfigurationProvider(new LiteralProvider(['auth' => $authConfig]));

        $client = ClientFactory::buildInstance($scrawler->getClientConfigurationProviders());

        $this->assertEquals($authConfig, $client->getConfig()['auth']);
    }
}
