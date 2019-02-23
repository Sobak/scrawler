<?php

namespace Sobak\Scrawler\Output;

use Sobak\Scrawler\Support\Utils;

class Outputter
{
    const OUTPUT_DIRECTORY = 'output';

    protected $directoryName;

    public function __construct($directoryName)
    {
        $this->directoryName = self::OUTPUT_DIRECTORY  . '/' . trim($directoryName, '/') . '/';

        if (
            (file_exists(self::OUTPUT_DIRECTORY) && is_dir(self::OUTPUT_DIRECTORY) === false) ||
            (file_exists($this->directoryName) && is_dir($this->directoryName) === false)
        ) {
            throw new \Exception('Output directory name taken by a file');
        }

        if (file_exists(self::OUTPUT_DIRECTORY) === false) {
            mkdir(self::OUTPUT_DIRECTORY);
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

    public function deleteOutput()
    {
        Utils::removeDirectoryRecursively($this->directoryName);
    }
}
