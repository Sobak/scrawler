<?php

namespace Sobak\Scrawler\Block\ObjectDefinition;

use Sobak\Scrawler\Block\FieldDefinition\FieldDefinitionInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;
use Sobak\Scrawler\Matcher\MatcherInterface;

interface ObjectDefinitionInterface
{
    public function __construct(MatcherInterface $matcher);

    public function addFieldDefinition(string $name, FieldDefinitionInterface $fieldDefinition);

    /**
     * @return FieldDefinitionInterface[]
     */
    public function getFieldDefinitions();

    public function removeFieldDefinition(string $name);

    public function getMatcher(): MatcherInterface;

    public function addResultWriter(string $name, ResultWriterInterface $resultWriter);

    public function getResultWriters();

    public function removeResultWriter(string $name);
}
