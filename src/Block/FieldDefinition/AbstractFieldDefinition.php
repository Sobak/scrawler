<?php

namespace Sobak\Scrawler\Block\FieldDefinition;

use Sobak\Scrawler\Matcher\MatcherInterface;

abstract class AbstractFieldDefinition implements FieldDefinitionInterface
{
    protected $matcher;

    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function serializeValue()
    {
        return $this->serializer($this->matcher->match());
    }
}
