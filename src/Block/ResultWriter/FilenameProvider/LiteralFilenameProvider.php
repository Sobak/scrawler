<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

class LiteralFilenameProvider extends AbstractFilenameProvider
{
    public function generateFilename(object $entity): string
    {
        return $this->configuration['filename'];
    }
}
