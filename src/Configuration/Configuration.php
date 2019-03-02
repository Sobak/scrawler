<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Block\ClientConfigurationProvider\ClientConfigurationProviderInterface;
use Sobak\Scrawler\Block\LogWriter\LogWriterInterface;
use Sobak\Scrawler\Block\UrlListProvider\UrlListProviderInterface;
use Sobak\Scrawler\Matcher\MatcherInterface;

class Configuration
{
    protected $baseUrl = null;

    /** @var ClientConfigurationProviderInterface[] */
    protected $clientConfigurationProviders = [];

    protected $operationName = null;

    /** @var LogWriterInterface[] */
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
        $this->clientConfigurationProviders[get_class($clientConfigurationProvider)] = $clientConfigurationProvider;

        return $this;
    }

    public function getClientConfigurationProviders()
    {
        return $this->clientConfigurationProviders;
    }

    public function removeClientConfigurationProvider(string $clientConfigurationProvider): self
    {
        unset($this->clientConfigurationProviders[$clientConfigurationProvider]);

        return $this;
    }

    public function addLogWriter(LogWriterInterface $logWriter): self
    {
        $this->logWriters[get_class($logWriter)] = $logWriter;

        return $this;
    }

    public function getLogWriters()
    {
        return $this->logWriters;
    }

    public function removeLogWriter(string $logWriter): self
    {
        unset($this->logWriters[$logWriter]);

        return $this;
    }

    public function addObjectDefinition(string $name, MatcherInterface $matcher, callable $configuration): self
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

    public function removeObjectDefinition(string $name): self
    {
        unset($this->objectDefinitions[$name]);

        return $this;
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
        $this->urlListProviders[get_class($urlListProvider)] = $urlListProvider;

        return $this;
    }

    public function getUrlListProviders()
    {
        return $this->urlListProviders;
    }

    public function removeUrlListProvider(string $name): self
    {
        unset($this->urlListProviders[$name]);

        return $this;
    }
}
