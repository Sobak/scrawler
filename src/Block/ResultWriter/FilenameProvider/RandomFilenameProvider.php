<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

use Sobak\Scrawler\Entity\EntityInterface;

class RandomFilenameProvider extends AbstractFilenameProvider
{
    public function generateFilename(EntityInterface $entity)
    {
        return uniqid();
    }
}
