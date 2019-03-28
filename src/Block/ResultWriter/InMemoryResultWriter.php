<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityMapper;

class InMemoryResultWriter extends AbstractResultWriter
{
    public static $results;

    public function write(object $entity): bool
    {
        if (isset($this->configuration['group'])) {
            self::$results[$this->configuration['group']][] = EntityMapper::entityToArray($entity);
        } else {
            self::$results[] = EntityMapper::entityToArray($entity);
        }

        return true;
    }
}
