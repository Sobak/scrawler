<?php

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Block\LogWriter\ConsoleLogWriter;
use Sobak\Scrawler\Block\LogWriter\TextfileLogWriter;

class DefaultConfigurationProvider
{
    public function setDefaultConfiguration(Configuration $configuration): Configuration
    {
        $configuration
            ->addLogWriter(new ConsoleLogWriter())
            ->addLogWriter(new TextfileLogWriter())
        ;

        return $configuration;
    }
}
