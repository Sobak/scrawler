<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

abstract class AbstractFilenameProvider implements FilenameProviderInterface
{
    protected $configuration;

    public function __construct(array $configuration = [])
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
