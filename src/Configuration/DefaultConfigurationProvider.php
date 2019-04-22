<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Psr\Log\LogLevel;
use Sobak\Scrawler\Block\ClientConfigurationProvider\LiteralProvider;
use Sobak\Scrawler\Block\LogWriter\ConsoleLogWriter;
use Sobak\Scrawler\Block\LogWriter\TextfileLogWriter;
use Sobak\Scrawler\Block\RobotsParser\DefaultRobotsParser;

class DefaultConfigurationProvider
{
    public function setDefaultConfiguration(Configuration $configuration): Configuration
    {
        $configuration
            ->addLogWriter(new ConsoleLogWriter(), LogLevel::INFO)
            ->addLogWriter(new TextfileLogWriter(), LogLevel::INFO)
            ->addClientConfigurationProvider(new LiteralProvider([
                'connect_timeout' => 5,
                'timeout' => 10,
            ]))
            ->setRobotsParser(new DefaultRobotsParser())
        ;

        return $configuration;
    }
}
