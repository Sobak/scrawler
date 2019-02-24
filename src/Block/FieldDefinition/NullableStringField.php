<?php

namespace Sobak\Scrawler\Block\FieldDefinition;

class NullableStringField extends AbstractFieldDefinition
{
    public function serializer($value): ?string
    {
        return !empty($string) ? (string) $value : null;
    }
}
