<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

class LiteralProvider extends AbstractClientConfigurationProvider
{
    public function __construct(array $configuration)
    {
        $this->setConfiguration($configuration);
    }
}
