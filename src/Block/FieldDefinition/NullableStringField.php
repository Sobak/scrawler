<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\FieldDefinition;

class NullableStringField extends AbstractFieldDefinition
{
    public function serializer($value): ?string
    {
        return !empty($value) ? (string) $value : null;
    }
}
