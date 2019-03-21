<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

abstract class AbstractResultWriter implements ResultWriterInterface
{
    protected $configuration;

    protected $entityName;

    public function __construct(string $entityName, array $configuration = [])
    {
        $this->configuration = $configuration;
        $this->entityName = $entityName;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function initializeResultWrites(): void
    {
        //
    }
}
