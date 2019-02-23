<?php

namespace Sobak\Scrawler;

use GuzzleHttp\Client;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ConfigurationChecker;

class Scrawler
{
    const VERSION = '0.1.0';

    /** @var Configuration */
    protected $configuration;

    protected $configurationPath;

    public function __construct($configurationPath)
    {
        $this->configurationPath = $configurationPath;
    }

    public function run()
    {
        $this->configuration = $this->loadConfiguration();
        $configurationCheck = $this->checkConfiguration($this->configuration);

        if ($configurationCheck === false) {
            die('configuration check failed');
        }

        $this->info('Attempting basic request');

        $client = new Client();

        $response = $client->request('GET', $this->configuration->getBaseUrl());

        dd($response);

        return 0;
    }

    protected function loadConfiguration(): Configuration
    {
        /** @noinspection PhpIncludeInspection */
        $configuration = require $this->configurationPath;

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

    protected function info($string)
    {
        foreach ($this->configuration->getLogWriters() as $logWriter) {
            $logWriter->info($string);
        }
    }
}
