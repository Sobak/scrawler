<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sobak\Scrawler\Block\ClientConfigurationProvider\ClientConfigurationProviderInterface;
use Sobak\Scrawler\Block\Matcher\ListMatcherInterface;
use Sobak\Scrawler\Block\RobotsParser\RobotsParserInterface;
use Sobak\Scrawler\Block\UrlListProvider\UrlListProviderInterface;

class Configuration
{
    protected $baseUrl = '';

    /** @var ClientConfigurationProviderInterface[] */
    protected $clientConfigurationProviders = [];

    protected $operationName = '';

    /** @var array */
    protected $logWriters = [];

    /** @var int */
    protected $maxCrawledUrls = 0;

    /** @var ObjectConfiguration[] */
    protected $objectDefinitions = [];

    /** @var ?RobotsParserInterface */
    protected $robotsParser;

    /** @var UrlListProviderInterface[] */
    protected $urlListProviders = [];

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function addClientConfigurationProvider(ClientConfigurationProviderInterface $clientConfigurationProvider): self
    {
        $this->clientConfigurationProviders[] = $clientConfigurationProvider;

        return $this;
    }

    public function getClientConfigurationProviders()
    {
        return $this->clientConfigurationProviders;
    }

    public function addLogWriter(LoggerInterface $logWriter, string $verbosity = LogLevel::DEBUG): self
    {
        $this->logWriters[] = [
            'class' => $logWriter,
            'verbosity' => $verbosity,
        ];

        return $this;
    }

    public function getLogWriters()
    {
        return $this->logWriters;
    }

    public function getMaxCrawledUrls(): int
    {
        return $this->maxCrawledUrls;
    }

    public function setMaxCrawledUrls(int $maxCrawledUrls): self
    {
        $this->maxCrawledUrls = $maxCrawledUrls;

        return $this;
    }

    public function addObjectDefinition(string $name, ListMatcherInterface $matcher, callable $configuration): self
    {
        $objectDefinition = new ObjectConfiguration($matcher);
        $configuration($objectDefinition);

        $this->objectDefinitions[$name] = $objectDefinition;

        return $this;
    }

    public function getObjectDefinitions()
    {
        return $this->objectDefinitions;
    }

    public function getOperationName()
    {
        return $this->operationName;
    }

    public function setOperationName(string $operationName): self
    {
        $this->operationName = $operationName;

        return $this;
    }

    public function getRobotsParser(): ?RobotsParserInterface
    {
        return $this->robotsParser;
    }

    public function setRobotsParser(?RobotsParserInterface $robotsParser): self
    {
        $this->robotsParser = $robotsParser;

        return $this;
    }

    public function addUrlListProvider(UrlListProviderInterface $urlListProvider): self
    {
        $this->urlListProviders[] = $urlListProvider;

        return $this;
    }

    public function getUrlListProviders()
    {
        return $this->urlListProviders;
    }
}
