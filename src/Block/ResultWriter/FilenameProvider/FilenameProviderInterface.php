<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter\FilenameProvider;

use Sobak\Scrawler\Entity\EntityInterface;

interface FilenameProviderInterface
{
    public function __construct(array $configuration = []);

    /**
     * Returns implementation-specific configuration set by the user.
     *
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * Generates a filename (with no extension) for a file holding single entity.
     *
     * @param  EntityInterface $entity
     * @return string
     */
    public function generateFilename(EntityInterface $entity): string;
}
