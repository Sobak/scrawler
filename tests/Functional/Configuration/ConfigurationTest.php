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
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Application must return the Configuration instance');

        $scrawler = new Scrawler(__DIR__ . '/config-wrong-object.php');
        $scrawler->run();
    }

    public function testEmptyConfigurationThrowsValidException(): void
    {
        // Makes sure that all configuration getters (which are used during
        // configuration checking process) were actually implemented

        $this->expectException(ConfigurationException::class);

        $scrawler = new Scrawler(__DIR__ . '/config-empty.php');
        $scrawler->run();
    }

    public function testMissingConfigurationFile(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('#^Could not find configuration at (.+)$#');

        $scrawler = new Scrawler(__DIR__ . '/missing-file.php');
        $scrawler->run();
    }
}
