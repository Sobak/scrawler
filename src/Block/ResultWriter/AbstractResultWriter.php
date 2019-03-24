<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

abstract class AbstractResultWriter implements ResultWriterInterface
{
    protected $configuration;

    protected $entityName;

    public function __construct(array $configuration = [])
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function getEntity(): string
    {
        return $this->entityName;
    }

    public function setEntity(string $entityName): ResultWriterInterface
    {
        $this->entityName = $entityName;

        return $this;
    }

    public function initializeResultWrites(): void
    {
        //
    }
}
