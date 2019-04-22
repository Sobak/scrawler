<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

interface ClientConfigurationProviderInterface
{
    /**
     * Gets the configuration hold by the provider.
     *
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * Sets the configuration hold by the provider.
     *
     * The array must be an array compatible with GuzzleHttp configuration format.
     *
     * @param array $configuration
     */
    public function setConfiguration(array $configuration): void;
}
