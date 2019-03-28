<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityMapper;

class JsonFileResultWriter extends FileResultWriter
{
    public function write(object $entity): bool
    {
        $output = json_encode(EntityMapper::entityToArray($entity), JSON_PRETTY_PRINT);

        return $this->writeToFile($output, 'json');
    }
}
