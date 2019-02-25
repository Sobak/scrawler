<?php

namespace Sobak\Scrawler\Block\FieldDefinition;

use Sobak\Scrawler\Matcher\MatcherInterface;

interface FieldDefinitionInterface
{
    public function __construct(MatcherInterface $matcher);

    public function getMatcher(): MatcherInterface;

    public function serializeValue();

    public function serializer($value);
}
