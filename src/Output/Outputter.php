<?php

namespace Sobak\Scrawler\Output;

use Sobak\Scrawler\Support\Utils;

class Outputter
{
    const OUTPUT_DIRECTORY = 'output';

    protected $directoryName;

    public function __construct(string $basePath, string $directoryName)
    {
        $outputDirectory = $basePath . '/' . self::OUTPUT_DIRECTORY;

        $this->directoryName = $outputDirectory  . '/' . trim($directoryName, '/') . '/';

        if (is_file($outputDirectory) || is_file($this->directoryName)) {
            throw new \Exception('Output directory name taken by a file');
        }

        if (file_exists($outputDirectory) === false) {
            mkdir($outputDirectory);
        }


        if (file_exists($this->directoryName)) {
            $this->deleteOutput();
        }

        mkdir($this->directoryName);
    }

    public function getDirectoryName()
    {
        return $this->directoryName;
    }

    public function appendToFile($filename, $contents)
    {
        file_put_contents($this->directoryName . $filename, $contents, FILE_APPEND);
    }

    public function writeToFile($filename, $contents)
    {
        file_put_contents($this->directoryName . $filename, $contents);
    }

    public function deleteFile($filename)
    {
        unlink($this->directoryName . $filename);
    }

    public function createDirectory($name, $nested = false)
    {
        if (is_file($this->directoryName . $name)) {
            throw new \Exception("Cannot create directory '$name', name already taken by the file'");
        }

        if (file_exists($this->directoryName . $name) === false) {
            mkdir($this->directoryName . $name, 0777, $nested);
        }
    }

    public function deleteOutput()
    {
        Utils::removeDirectoryRecursively($this->directoryName);
    }
}
