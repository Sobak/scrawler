<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

interface FileResultWriterInterface extends ResultWriterInterface
{
    /**
     * Returns the filename (with no extension) to use for the result file.
     *
     * @return string
     */
    public function getFilename(): string;

    public function setFilename(string $filename): void;
}
