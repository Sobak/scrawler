<?php

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityInterface;

class JsonFileResultWriter extends FileResultWriter
{
    public function write(EntityInterface $entity): bool
    {
        $output = json_encode($entity->toArray(), JSON_PRETTY_PRINT);

        return $this->writeToFile($output, 'json');
    }
}
