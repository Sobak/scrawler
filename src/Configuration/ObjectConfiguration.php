<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use ArrayIterator;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;
use Sobak\Scrawler\Matcher\MatcherInterface;

class ObjectConfiguration
{
    protected $entityMappings = [];

    /** @var MatcherInterface[] */
    protected $fieldDefinitions;

    protected $matcher;

    /** @var ResultWriterInterface[] */
    protected $resultWriters = [];

    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function getMatches(): ArrayIterator
    {
        return $this->matcher->match();
    }

    public function addEntityMapping(string $entityClass): self
    {
        $this->entityMappings[$entityClass] = $entityClass;

        return $this;
    }

    public function getEntityMappings()
    {
        return $this->entityMappings;
    }

    public function removeEntityMapping(string $entityClass): self
    {
        unset($this->entityMappings[$entityClass]);

        return $this;
    }

    public function addFieldDefinition(string $name, MatcherInterface $fieldDefinition): self
    {
        $this->fieldDefinitions[$name] = $fieldDefinition;

        return $this;
    }

    public function getFieldDefinitions()
    {
        return $this->fieldDefinitions;
    }

    public function removeFieldDefinition(string $name): self
    {
        unset($this->fieldDefinitions[$name]);

        return $this;
    }

    public function addResultWriter(string $entityClass, ResultWriterInterface $resultWriter): self
    {
        $this->resultWriters[$entityClass] = $resultWriter;

        return $this;
    }

    public function getResultWriters()
    {
        return $this->resultWriters;
    }

    public function removeResultWriter(string $entityClass): self
    {
        unset($this->resultWriters[$entityClass]);

        return $this;
    }
}
