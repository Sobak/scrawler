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
     * Fires once before calling write() on any of the entities found for single result writer.
     *
     * Might be useul to prepare result storage in any way, like create a directory,
     * perform initial checks, caching something to improve performance and so on...
     */
    public function initializeResultWrites(): void;

    /**
     * Writes single entitty and returns the operation status.
     *
     * @param  EntityInterface $entity
     * @return bool
     */
    public function write(EntityInterface $entity): bool;
}
