<?php

declare(strict_types=1);

namespace Tests\Utils;

use Sobak\Scrawler\Block\ResultWriter\AbstractResultWriter;
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
