<?php

namespace Sobak\Scrawler\Block\FieldDefinition;

class IntegerField extends AbstractFieldDefinition
{
    public function serializer($value): int
    {
        return (int) $value;
    }
}
