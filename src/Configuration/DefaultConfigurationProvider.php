<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Block\ClientConfigurationProvider\ScrawlerUserAgentProvider;
use Sobak\Scrawler\Block\LogWriter\ConsoleLogWriter;
use Sobak\Scrawler\Block\LogWriter\TextfileLogWriter;

class DefaultConfigurationProvider
{
    public function setDefaultConfiguration(Configuration $configuration): Configuration
    {
        $configuration
            ->addLogWriter(new ConsoleLogWriter())
            ->addLogWriter(new TextfileLogWriter())
            ->addClientConfigurationProvider(new ScrawlerUserAgentProvider())
        ;

        return $configuration;
    }
}
