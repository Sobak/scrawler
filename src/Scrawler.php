<?php

declare(strict_types=1);

namespace Sobak\Scrawler;

use Sobak\Scrawler\Client\ClientFactory;
use Sobak\Scrawler\Client\Request\Request;
use Sobak\Scrawler\Client\Response\Elements\Url;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ConfigurationChecker;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Support\LogWriter;
use Sobak\Scrawler\Support\Utils;

class Scrawler
{
    const VERSION = '0.2.0-dev';

    /** @var Configuration */
    protected $configuration;

    protected $logWriter;

    protected $output;

    public function __construct(Configuration $configuration, string $outputDirectory)
    {
        $this->checkConfiguration($configuration);

        $this->output = new Outputter(
            $outputDirectory,
            Utils::slugify($configuration->getOperationName())
        );

        $this->configuration = $configuration;
        $this->logWriter = new LogWriter($this->configuration->getLogWriters(), $this->output);
    }

    public function run()
    {
        $this->logWriter->info('Running "' . $this->configuration->getOperationName() . '" operation');

        $client = ClientFactory::applyCustomConfiguration($this->configuration->getClientConfigurationProviders());
        $initialUrl = new Url($this->configuration->getBaseUrl());

        $request = new Request($client, $this->configuration, $this->output);
        $request->makeRequest($initialUrl, []);

        return 0;
    }

    protected function checkConfiguration(Configuration $configuration): bool
    {
        $configurationChecker = new ConfigurationChecker();

        return $configurationChecker->checkConfiguration($configuration);
    }
}
