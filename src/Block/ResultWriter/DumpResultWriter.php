<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityInterface;

class DumpResultWriter extends AbstractResultWriter
{
    public function write(EntityInterface $entity): bool
    {
        dump($entity);

        return true;
    }
}
