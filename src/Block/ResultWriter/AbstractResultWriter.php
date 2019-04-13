<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Psr\Log\LoggerInterface;

abstract class AbstractResultWriter implements ResultWriterInterface
{
    protected $configuration;

    protected $entityName;

    /** @var LoggerInterface */
    protected $logWriter;

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

    public function getLogWriter(): LoggerInterface
    {
        return $this->logWriter;
    }

    public function setLogWriter($logWriter): ResultWriterInterface
    {
        $this->logWriter = $logWriter;

        return $this;
    }

    public function initializeResultWrites(): void
    {
        //
    }
}
