<?php

namespace Sobak\Scrawler;

use GuzzleHttp\Client;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ConfigurationChecker;

class Scrawler
{
    const VERSION = '0.1.0';

    protected $configuration;
    protected $configurationPath;

    public function __construct($configurationPath)
    {
        $this->configurationPath = $configurationPath;
    }

    public function run()
    {
        $this->configuration = $configuration = $this->loadConfiguration();
        $configurationCheck = $this->checkConfiguration($configuration);

        if ($configurationCheck === false) {
            die('configuration check failed');
        }

        $client = new Client();

        $response = $client->request('GET', $configuration->getBaseUrl());

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
}
