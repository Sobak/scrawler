<?php

namespace Tests\Utils;

use Sobak\Scrawler\Output\OutputManagerInterface;

class InMemoryOutputManager implements OutputManagerInterface
{
    public static $filesystem;

    protected $directoryName;

    public function __construct(string $directoryName)
    {
        $this->directoryName = $directoryName;

        $this->deleteOutput();
    }

    public function getDirectoryName(): string
    {
        return $this->directoryName;
    }

    public function appendToFile($filename, $contents): void
    {
        $currentContents = self::$filesystem[$this->directoryName][$filename] ?? '';

        self::$filesystem[$this->directoryName][$filename] = $currentContents . $contents;
    }

    public function writeToFile($filename, $contents): void
    {
        self::$filesystem[$this->directoryName][$filename] = $contents;
    }

    public function deleteFile($filename): void
    {
        unset(self::$filesystem[$this->directoryName][$filename]);
    }

    public function createDirectory($name, $nested = false): void
    {
        if (
            isset(self::$filesystem[$this->directoryName][$name])
            && is_array(self::$filesystem[$this->directoryName][$name]) === false
        ) {
            throw new \Exception("Cannot create directory '$name', name already taken by the file'");
        }
    }

    public function deleteOutput(): void
    {
        self::$filesystem[$this->directoryName] = [];
    }
}
