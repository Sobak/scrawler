<?php

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityMapper;

class AbstractResultWriter implements ResultWriterInterface
{
    protected $configuration;

    protected $entity;

    protected $entityName;

    protected $results;

    public function __construct(string $entityName, array $configuration = [])
    {
        $this->configuration = $configuration;
        $this->entityName = $entityName;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setResults($results)
    {
        $this->results = $results;
    }

    public function mapResultsToEntity()
    {
        $entityMapper = new EntityMapper();

        $this->entity = $entityMapper->mapResultsToEntity($this->results, $this->entityName);
    }
}
