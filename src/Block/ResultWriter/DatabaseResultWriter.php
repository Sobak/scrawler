<?php

namespace Sobak\Scrawler\Block\ResultWriter;

class DatabaseResultWriter extends AbstractResultWriter
{
    public function write(object $entity): bool
    {
        dump('writing', $entity);

        return true;
    }
}
