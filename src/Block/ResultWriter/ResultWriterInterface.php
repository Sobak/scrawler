<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Sobak\Scrawler\Entity\EntityInterface;

interface ResultWriterInterface
{
    public function __construct(array $configuration = []);

    public function getConfiguration(): array;

    public function write(EntityInterface $entity): bool;
}
