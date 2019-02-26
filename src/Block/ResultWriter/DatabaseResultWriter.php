<?php

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityInterface;

class DatabaseResultWriter extends AbstractResultWriter
{
    public function write(EntityInterface $entity): bool
    {
        dump('writing', $entity);

        return true;
    }
}
