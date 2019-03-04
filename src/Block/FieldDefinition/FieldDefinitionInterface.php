<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\FieldDefinition;

use Sobak\Scrawler\Matcher\MatcherInterface;

interface FieldDefinitionInterface
{
    public function __construct(MatcherInterface $matcher);

    /**
     * Returs the underlying matcher which provides the field value.
     *
     * @return MatcherInterface
     */
    public function getMatcher(): MatcherInterface;

    /**
     * Returns the field value in its desired format.
     *
     * Typically calls something like $this->>serializer($this->matcher->match()
     *
     * @return mixed
     */
    public function serializeValue();

    /**
     * Formats the value using field-specific logic.
     *
     * @param  mixed $value Field value read by the scrapper
     * @return mixed
     */
    public function serializer($value);
}
