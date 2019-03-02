<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

interface ClientConfigurationProviderInterface
{
    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;
}
