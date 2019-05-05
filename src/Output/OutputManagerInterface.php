<?php

namespace Sobak\Scrawler\Output;

interface OutputManagerInterface
{
    /**
     * Returns name of the output directory.
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
     * Create file handle to use with builtin PHP functions.
     *
     * @param string $filename
     * @param string $mode
     * @return resource
     */
    public function createFileHandle($filename, $mode);

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