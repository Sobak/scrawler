<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

interface ClientConfigurationProviderInterface
{
    /**
     * Sets the configuration hold by the provider.
     *
     * The array must be an array compatible with GuzzleHttp configuration format.
     *
     * @return array
     */
    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;
}
