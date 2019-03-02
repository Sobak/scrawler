<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\LogWriter;

use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

class TextfileLogWriter implements LogWriterInterface, OutputWriterInterface
{
    /** @var Outputter */
    protected $outputter;

    public function debug($string): void
    {
        $this->writeLine('debug', $string);
    }

    public function error($string): void
    {
        $this->writeLine('error', $string);
    }

    public function info($string): void
    {
        $this->writeLine('info', $string);
    }

    public function warning($string): void
    {
        $this->writeLine('warning', $string);
    }

    public function getOutputter(): Outputter
    {
        return $this->outputter;
    }

    public function setOutputter(Outputter $outputter): void
    {
        $this->outputter = $outputter;
    }

    protected function writeLine($level, $message): void
    {
        $datetime = date('Y-m-d H:i:s');
        $level = strtoupper($level);

        $line = "[$datetime][$level] $message\n";

        $this->outputter->appendToFile('crawler.log', $line);
    }
}
