<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ResultWriter;

use Exception;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\FilenameProviderInterface;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

abstract class FileResultWriter extends AbstractResultWriter implements
    FileResultWriterInterface,
    OutputWriterInterface
{
    protected $directory;

    protected $filename;

    /** @var Outputter */
    protected $outputter;

    public function __construct(string $entityName, array $configuration = [])
    {
        if (
            isset($configuration['filename']) === false
            || ($configuration['filename'] instanceof FilenameProviderInterface) === false
        ) {
            throw new Exception("For the FileResultWriter you must set the FilenameProvider under 'filename' key");
        }

        parent::__construct($entityName, $configuration);
    }

    public function getFilenameProvider(): FilenameProviderInterface
    {
        return $this->configuration['filename'];
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getOutputter(): Outputter
    {
        return $this->outputter;
    }

    public function setOutputter(Outputter $outputter): void
    {
        $this->outputter = $outputter;
    }

    public function initializeResultWrites(): void
    {
        $directory = '';
        if (isset($this->configuration['directory'])) {
            $directory = trim($this->configuration['directory'] ?? '', '/') . '/';

            $this->outputter->createDirectory($directory, true);
        }

        $this->directory = $directory;
    }

    protected function writeToFile(string $contents, string $extension): bool
    {
        $filename = $this->directory . $this->filename . '.' . $extension;
        $this->outputter->writeToFile($filename, $contents);

        return true;
    }
}
