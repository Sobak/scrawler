<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityMapper;

class TemplateFileResultWriter extends FileResultWriter
{
    const REGEX_VARIABLE = '#{{[ \t]*?([a-zA-Z0-9_]+)[ \t]*?}}#';

    public function write(object $entity): bool
    {
        $entityArray = EntityMapper::entityToArray($entity);

        $output = preg_replace_callback(self::REGEX_VARIABLE, function ($matches) use($entityArray) {
            return $entityArray[$matches[1]] ?? $matches[0];
        }, $this->configuration['template']);

        return $this->writeToFile($output, $this->configuration['extension'] ?? null);
    }
}
