<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Sobak\Scrawler\Block\LogWriter\ConsoleLogWriter;
use Sobak\Scrawler\Block\LogWriter\TextfileLogWriter;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ConfigurationChecker;
use Sobak\Scrawler\Configuration\ConfigurationException;
use Sobak\Scrawler\Configuration\DefaultConfigurationProvider;
use Sobak\Scrawler\Scrawler;

class ConfigurationTest extends TestCase
{
    public function testEmptyConfigurationThrowsValidException(): void
    {
        $this->expectException(ConfigurationException::class);

        $scrawler = new Scrawler(new Configuration(), __DIR__);
        $scrawler->run();
    }

    public function testBaseUrlRequirement(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage("Required configuration option 'baseUrl' not set");

        $scrawler = new Configuration();

        $checker = new ConfigurationChecker();
        $checker->checkConfiguration($scrawler);
    }

    public function testOperationNameRequirement(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage("Required configuration option 'operationName' not set");

        $scrawler = new Configuration();
        $scrawler->setBaseUrl('http://example.com');

        $checker = new ConfigurationChecker();
        $checker->checkConfiguration($scrawler);
    }

    public function testUrlListProviderRequirement(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('At least one UrlListProvider must be set');

        $scrawler = new Configuration();
        $scrawler->setBaseUrl('http://example.com');
        $scrawler->setOperationName('test');

        $checker = new ConfigurationChecker();
        $checker->checkConfiguration($scrawler);
    }

    public function testDefaultConfigurationProvider(): void
    {
        $scrawler = new Configuration();
        $scrawler = (new DefaultConfigurationProvider())->setDefaultConfiguration($scrawler);

        $logWriters = $scrawler->getLogWriters();

        $this->assertInstanceOf(ConsoleLogWriter::class, $logWriters[0]['class']);
        $this->assertInstanceOf(TextfileLogWriter::class, $logWriters[1]['class']);
        $this->assertEquals(LogLevel::INFO, $logWriters[0]['verbosity']);
        $this->assertEquals(LogLevel::INFO, $logWriters[1]['verbosity']);
    }
}
