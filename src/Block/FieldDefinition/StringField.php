<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\FieldDefinition;

class StringField extends AbstractFieldDefinition
{
    public function serializer($value): string
    {
        return (string) $value;
    }
}
