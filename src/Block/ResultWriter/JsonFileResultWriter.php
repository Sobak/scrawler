<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityMapper;

class JsonFileResultWriter extends FileResultWriter
{
    public function write(object $entity): bool
    {
        $options = $this->configuration['options'] ?? JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
        $output = json_encode(EntityMapper::entityToArray($entity), $options);

        return $this->writeToFile($output, 'json');
    }
}
