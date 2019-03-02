<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use ArrayIterator;
use Sobak\Scrawler\Block\FieldDefinition\AbstractFieldDefinition;
use Sobak\Scrawler\Block\FieldDefinition\FieldDefinitionInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;

class ObjectConfiguration extends AbstractFieldDefinition
{
    protected $entityMappings = [];

    /** @var FieldDefinitionInterface[] */
    protected $fieldDefinitions;

    /** @var ResultWriterInterface[] */
    protected $resultWriters = [];

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

    public function addFieldDefinition(string $name, FieldDefinitionInterface $fieldDefinition): self
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

    public function serializeValue(): ArrayIterator
    {
        return $this->serializer($this->matcher->match());
    }

    public function serializer($value)
    {
        return $value;
    }
}
