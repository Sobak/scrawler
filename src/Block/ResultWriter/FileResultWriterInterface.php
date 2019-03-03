<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

interface FileResultWriterInterface extends ResultWriterInterface
{
    public function getFilename(): string;

    public function setFilename(string $filename): void;
}
