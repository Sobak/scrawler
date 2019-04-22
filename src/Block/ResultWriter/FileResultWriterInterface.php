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

    /**
     * Sets the filename (with no extension included) to use for the result file.
     *
     * @param string $filename
     */
    public function setFilename(string $filename): void;
}
