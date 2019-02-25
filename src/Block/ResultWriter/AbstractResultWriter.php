<?php

namespace Sobak\Scrawler\Block\ResultWriter;

abstract class AbstractResultWriter implements ResultWriterInterface
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
