<?php

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

class AbstractClientConfigurationProvider implements ClientConfigurationProviderInterface
{
    protected $configuration;

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }
}
