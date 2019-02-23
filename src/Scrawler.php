<?php

namespace Sobak\Scrawler;

use GuzzleHttp\Client;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ConfigurationChecker;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Support\LogWriter;
use Sobak\Scrawler\Support\Utils;

class Scrawler
{
    const VERSION = '0.1.0';

    /** @var Configuration */
    protected $configuration;

    protected $logWriter;

    protected $output;

    public function __construct($configurationPath)
    {
        $this->configuration = $this->loadConfiguration($configurationPath);
        $this->checkConfiguration($this->configuration);

        $this->output = new Outputter(Utils::slugify($this->configuration->getOperationName()));
        $this->logWriter = new LogWriter($this->configuration->getLogWriters(), $this->output);
    }

    public function run()
    {
        $this->logWriter->info('Running "' . $this->configuration->getOperationName() . '" operation');

        $client = new Client();

        $response = $client->request('GET', $this->configuration->getBaseUrl());

        dd($response);

        return 0;
    }

    protected function loadConfiguration($configurationPath): Configuration
    {
        /** @noinspection PhpIncludeInspection */
        $configuration = require $configurationPath;

        if (($configuration instanceof Configuration) === false) {
            throw new \Exception('Application must return the Configuration instance');
        }

        return $configuration;
    }

    protected function checkConfiguration(Configuration $configuration): bool
    {
        $configurationChecker = new ConfigurationChecker();

        return $configurationChecker->checkConfiguration($configuration);
    }
}
