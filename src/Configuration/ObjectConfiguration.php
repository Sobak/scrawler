<?php

namespace Sobak\Scrawler\Configuration;

use ArrayIterator;
use Sobak\Scrawler\Block\FieldDefinition\AbstractFieldDefinition;
use Sobak\Scrawler\Block\FieldDefinition\FieldDefinitionInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;

class ObjectConfiguration extends AbstractFieldDefinition
{
    /** @var FieldDefinitionInterface[] */
    protected $fieldDefinitions;

    /** @var ResultWriterInterface[] */
    protected $resultWriters = [];

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

    public function addResultWriter(string $name, ResultWriterInterface $resultWriter)
    {
        $this->resultWriters[$name] = $resultWriter;

        return $this;
    }

    public function getResultWriters()
    {
        return $this->resultWriters;
    }

    public function removeResultWriter(string $name)
    {
        unset($this->resultWriters[$name]);

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
