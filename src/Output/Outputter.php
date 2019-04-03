<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Output;

use Sobak\Scrawler\Support\Utils;

class Outputter
{
    protected $directoryName;

    public function __construct(string $basePath, string $directoryName)
    {
        $this->directoryName = $basePath  . '/' . trim($directoryName, '/') . '/';

        if (is_file($basePath) || is_file($this->directoryName)) {
            throw new \Exception('Output directory name taken by a file');
        }

        if (file_exists($basePath) === false) {
            mkdir($basePath, 0777, true);
        }


        if (file_exists($this->directoryName)) {
            $this->deleteOutput();
        }

        mkdir($this->directoryName);
    }

    public function getDirectoryName(): string
    {
        return $this->directoryName;
    }

    public function appendToFile($filename, $contents): void
    {
        file_put_contents($this->directoryName . $filename, $contents, FILE_APPEND);
    }

    public function writeToFile($filename, $contents): void
    {
        file_put_contents($this->directoryName . $filename, $contents);
    }

    public function deleteFile($filename): void
    {
        unlink($this->directoryName . $filename);
    }

    public function createDirectory($name, $nested = false): void
    {
        if (is_file($this->directoryName . $name)) {
            throw new \Exception("Cannot create directory '$name', name already taken by the file'");
        }

        if (file_exists($this->directoryName . $name) === false) {
            mkdir($this->directoryName . $name, 0777, $nested);
        }
    }

    public function deleteOutput(): void
    {
        Utils::removeDirectoryRecursively($this->directoryName);
    }
}
