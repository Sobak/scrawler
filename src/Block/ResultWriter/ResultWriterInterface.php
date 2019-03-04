<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityInterface;

interface ResultWriterInterface
{
    public function __construct(array $configuration = []);

    /**
     * Returns implementation-specific configuration provided by the user.
     *
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * Writes single entitty and returns the operation status.
     *
     * @param  EntityInterface $entity
     * @return bool
     */
    public function write(EntityInterface $entity): bool;
}
