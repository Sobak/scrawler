<?php

declare(strict_types=1);

namespace Tests\Functional\ConfigurationRequired;

use Exception;
use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Configuration\ConfigurationException;
use Sobak\Scrawler\Scrawler;

class ConfigurationTest extends TestCase
{
    public function testConfigurationObjectTypeChecking(): void
    {
        $this->expectException(\TypeError::class);

        $config = require 'config-wrong-object.php';

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();
    }

    public function testEmptyConfigurationThrowsValidException(): void
    {
        // Makes sure that all configuration getters (which are used during
        // configuration checking process) were actually implemented

        $this->expectException(ConfigurationException::class);

        $config = require 'config-empty.php';

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();
    }
}
