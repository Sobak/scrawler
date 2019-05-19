<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Block\Matcher\ListMatcherInterface;
use Sobak\Scrawler\Block\Matcher\MatcherInterface;
use Sobak\Scrawler\Block\ResultWriter\ResultWriterInterface;

class ObjectConfiguration
{
    protected static $matchedObjects = [];

    protected $entityMappings = [];

    /** @var MatcherInterface[] */
    protected $fieldDefinitions;

    protected $matcher;

    protected $once = false;

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

    public function addFieldDefinition(string $name, MatcherInterface $fieldDefinition): self
    {
        $this->fieldDefinitions[$name] = $fieldDefinition;

        return $this;
    }

    public function getFieldDefinitions()
    {
        return $this->fieldDefinitions;
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

    public function once(): self
    {
        $this->once = true;

        return $this;
    }

    public function isOnce(): bool
    {
        return $this->once;
    }

    public function markAsMatched(): void
    {
        self::$matchedObjects[] = spl_object_id($this);
    }

    public function wasOnceMatched(): bool
    {
        return in_array(spl_object_id($this), self::$matchedObjects);
    }
}
