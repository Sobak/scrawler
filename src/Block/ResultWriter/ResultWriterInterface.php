<?php

namespace Sobak\Scrawler\Block\ResultWriter;

interface ResultWriterInterface
{
    public function __construct(array $configuration = []);

    public function getConfiguration(): array;

    public function write(object $entity): bool;
}
