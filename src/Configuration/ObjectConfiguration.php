<?php

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

    public function addEntityMapping(string $entityClass)
    {
        $this->entityMappings[$entityClass] = $entityClass;

        return $this;
    }

    public function getEntityMappings()
    {
        return $this->entityMappings;
    }

    public function removeEntityMapping(string $entityClass)
    {
        unset($this->entityMappings[$entityClass]);

        return $this;
    }

    public function addFieldDefinition(string $name, FieldDefinitionInterface $fieldDefinition)
    {
        $this->fieldDefinitions[$name] = $fieldDefinition;

        return $this;
    }

    public function getFieldDefinitions()
    {
        return $this->fieldDefinitions;
    }

    public function removeFieldDefinition(string $name)
    {
        unset($this->fieldDefinitions[$name]);

        return $this;
    }

    public function addResultWriter(string $entityClass, ResultWriterInterface $resultWriter)
    {
        $this->resultWriters[$entityClass] = $resultWriter;

        return $this;
    }

    public function getResultWriters()
    {
        return $this->resultWriters;
    }

    public function removeResultWriter(string $entityClass)
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
