<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityInterface;

class InMemoryResultWriter extends AbstractResultWriter
{
    public static $results;

    public function write(EntityInterface $entity): bool
    {
        if (isset($this->configuration['group'])) {
            self::$results[$this->configuration['group']][] = $entity->toArray();
        } else {
            self::$results[] = $entity->toArray();
        }

        return true;
    }
}
