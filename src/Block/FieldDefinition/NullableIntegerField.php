<?php

namespace Sobak\Scrawler\Block\FieldDefinition;

class NullableIntegerField extends AbstractFieldDefinition
{
    public function serializer($value): ?string
    {
        return !empty($value) ? (int) $value : null;
    }
}
