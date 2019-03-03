<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

use Sobak\Scrawler\Entity\EntityInterface;

interface FilenameProviderInterface
{
    public function __construct(array $configuration = []);

    public function getConfiguration(): array;

    public function generateFilename(EntityInterface $entity);
}
