<?php

namespace Sobak\Scrawler\Output;

interface OutputManagerInterface
{
    /**
     * OutputManager constructor.
     *
     * Typically it is used to initialize storage space (e.g. create directories)
     * and perform checks required to ensure further I/O operations will be
     * successful.
     *
     * @param string|null $basePath Base path Scrawler was called at (when using CLI)
     * @param string $operationName User-provided operation name
     */
    public function __construct(string $operationName, ?string $basePath = null);

    /**
     * Return name of the output directory.
     *
     * @return string
     */
    public function getDirectoryName(): string;

    /**
     * Append contents to given file.
     *
     * @param string $filename
     * @param mixed $contents
     */
    public function appendToFile($filename, $contents): void;

    /**
     * Write contents to file.
     *
     * @param string $filename
     * @param mixed $contents
     */
    public function writeToFile($filename, $contents): void;

    /**
     * Delete specific file.
     *
     * @param string $filename
     */
    public function deleteFile($filename): void;

    /**
     * Create directory or nested directories.
     *
     * @param string $name
     * @param bool $nested
     */
    public function createDirectory($name, $nested = false): void;

    /**
     * Delete output directory.
     */
    public function deleteOutput(): void;
}
