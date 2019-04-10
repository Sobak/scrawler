<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Psr\Log\LoggerInterface;
use Sobak\Scrawler\Block\ClientConfigurationProvider\ClientConfigurationProviderInterface;
use Sobak\Scrawler\Block\UrlListProvider\UrlListProviderInterface;
use Sobak\Scrawler\Matcher\ListMatcherInterface;

class Configuration
{
    protected $baseUrl = '';

    /** @var ClientConfigurationProviderInterface[] */
    protected $clientConfigurationProviders = [];

    protected $operationName = '';

    /** @var LoggerInterface[] */
    protected $logWriters = [];

    /** @var ObjectConfiguration[] */
    protected $objectDefinitions = [];

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

    public function addLogWriter(LoggerInterface $logWriter): self
    {
        $this->logWriters[] = $logWriter;

        return $this;
    }

    public function getLogWriters()
    {
        return $this->logWriters;
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
