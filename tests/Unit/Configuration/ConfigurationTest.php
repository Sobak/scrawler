<?php

declare(strict_types=1);

namespace Tests\Unit\Configuration;

use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ConfigurationException;
use Sobak\Scrawler\Scrawler;

class ConfigurationTest extends TestCase
{
    public function testConfigurationObjectTypeChecking(): void
    {
        $this->expectException(\TypeError::class);

        $scrawler = new Scrawler(new \stdClass(), __DIR__);
        $scrawler->run();
    }

    public function testEmptyConfigurationThrowsValidException(): void
    {
        $this->expectException(ConfigurationException::class);

        $scrawler = new Scrawler(new Configuration(), __DIR__);
        $scrawler->run();
    }
}
