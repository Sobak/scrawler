<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;
use Sobak\Scrawler\Matcher\ListMatcherInterface;
use Sobak\Scrawler\Matcher\MatcherInterface;

class ObjectConfiguration
{
    protected $entityMappings = [];

    /** @var MatcherInterface[] */
    protected $fieldDefinitions;

    protected $matcher;

    /** @var array */
    protected $resultWriters = [];

    public function __construct(ListMatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public function getMatcher(): ListMatcherInterface
    {
        return $this->matcher;
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
        $this->resultWriters[$entityClass][] = $resultWriter;

        return $this;
    }

    public function getResultWriters()
    {
        return $this->resultWriters;
    }
}
