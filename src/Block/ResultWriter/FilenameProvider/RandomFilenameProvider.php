<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

class RandomFilenameProvider extends AbstractFilenameProvider
{
    public function generateFilename(object $entity): string
    {
        return uniqid();
    }
}
