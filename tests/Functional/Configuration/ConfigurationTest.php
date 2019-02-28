<?php

namespace Tests\Functional\ConfigurationRequired;

use Exception;
use PHPUnit\Framework\TestCase;
use Sobak\Scrawler\Configuration\ConfigurationException;
use Sobak\Scrawler\Scrawler;

class ConfigurationTest extends TestCase
{
    public function testConfigurationObjectTypeChecking()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Application must return the Configuration instance');

        $scrawler = new Scrawler(__DIR__ . '/config-wrong-object.php');
        $scrawler->run();
    }

    public function testEmptyConfigurationThrowsValidException()
    {
        // Makes sure that all configuration getters (which are used during
        // configuration checking process) were actually implemented

        $this->expectException(ConfigurationException::class);

        $scrawler = new Scrawler(__DIR__ . '/config-empty.php');
        $scrawler->run();
    }
}
