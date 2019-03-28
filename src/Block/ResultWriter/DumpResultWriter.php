<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

class DumpResultWriter extends AbstractResultWriter
{
    public function write(object $entity): bool
    {
        dump($entity);

        return true;
    }
}
