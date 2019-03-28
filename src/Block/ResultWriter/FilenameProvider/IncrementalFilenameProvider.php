<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

class IncrementalFilenameProvider extends AbstractFilenameProvider
{
    public $start;

    public function __construct(array $configuration = [])
    {
        if (isset($configuration['start']) === false) {
            $configuration['start'] = 1;
        }

        parent::__construct($configuration);
    }

    public function generateFilename(object $entity): string
    {
        return (string) $this->configuration['start']++;
    }
}
