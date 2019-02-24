<?php

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Block\ClientConfigurationProvider\ClientConfigurationProviderInterface;
use Sobak\Scrawler\Block\FieldDefinition\FieldDefinitionInterface;
use Sobak\Scrawler\Block\LogWriter\LogWriterInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;

class Configuration
{
    protected $baseUrl = null;

    /** @var ClientConfigurationProviderInterface[] */
    protected $clientConfigurationProviders = [];

    /** @var FieldDefinitionInterface[] */
    protected $fields;

    protected $operationName = null;

    /** @var LogWriterInterface[] */
    protected $logWriters = [];

    /** @var ResultWriterInterface[] */
    protected $resultWriters = [];

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function addClientConfigurationProvider(ClientConfigurationProviderInterface $clientConfigurationProvider)
    {
        $this->clientConfigurationProviders[get_class($clientConfigurationProvider)] = $clientConfigurationProvider;

        return $this;
    }

    public function getClientConfigurationProviders()
    {
        return $this->clientConfigurationProviders;
    }

    public function removeClientConfigurationProvider(string $clientConfigurationProvider)
    {
        unset($this->clientConfigurationProviders[$clientConfigurationProvider]);

        return $this;
    }

    public function addField(string $name, FieldDefinitionInterface $fieldDefinition)
    {
        $this->fields[$name] = $fieldDefinition;

        return $this;
    }

    /**
     * @param FieldDefinitionInterface[] $fields
     * @return self
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $name => $fieldDefinition) {
            $this->addField($name, $fieldDefinition);
        }

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function removeField(string $name)
    {
        unset ($this->fields[$name]);

        return $this;
    }

    public function addLogWriter(LogWriterInterface $logWriter)
    {
        $this->logWriters[get_class($logWriter)] = $logWriter;

        return $this;
    }

    public function getLogWriters()
    {
        return $this->logWriters;
    }

    public function removeLogWriter(string $logWriter)
    {
        unset($this->logWriters[$logWriter]);

        return $this;
    }

    public function getOperationName()
    {
        return $this->operationName;
    }

    public function setOperationName($operationName)
    {
        $this->operationName = $operationName;

        return $this;
    }

    public function addResultWriter(string $name, ResultWriterInterface $resultWriter)
    {
        $this->resultWriters[$name] = $resultWriter;

        return $this;
    }

    public function getResultWriters()
    {
        return $this->resultWriters;
    }

    public function removeResultWriter(string $name)
    {
        unset($this->resultWriters[$name]);

        return $this;
    }
}
