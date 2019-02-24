<?php

namespace Sobak\Scrawler\Block\ResultWriter;

interface ResultWriterInterface
{
    public function __construct(string $entityName, array $configuration = []);

    public function getConfiguration(): array;

    public function getEntity(): object;

    public function getEntityName(): string;

    public function getResults();

    public function setResults($results);

    public function mapResultsToEntity();
}
