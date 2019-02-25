<?php

namespace Sobak\Scrawler\Block\ObjectDefinition;

use Sobak\Scrawler\Block\FieldDefinition\FieldDefinitionInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;
use Sobak\Scrawler\Matcher\MatcherInterface;

class ObjectDefinition implements ObjectDefinitionInterface
{
    /** @var FieldDefinitionInterface[] */
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
        unset ($this->fieldDefinitions[$name]);

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
}
