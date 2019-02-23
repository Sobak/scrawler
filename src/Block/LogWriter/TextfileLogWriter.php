<?php

namespace Sobak\Scrawler\Block\LogWriter;

use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

class TextfileLogWriter implements LogWriterInterface, OutputWriterInterface
{
    /** @var Outputter */
    protected $outputter;

    public function debug($string)
    {
        $this->writeLine('debug', $string);
    }

    public function error($string)
    {
        $this->writeLine('error', $string);
    }

    public function info($string)
    {
        $this->writeLine('info', $string);
    }

    public function warning($string)
    {
        $this->writeLine('warning', $string);
    }

    public function getOutputter()
    {
        return $this->outputter;
    }

    public function setOutputter(Outputter $outputter)
    {
        $this->outputter = $outputter;
    }

    protected function writeLine($level, $message)
    {
        $datetime = date('Y-m-d H:i:s');
        $level = strtoupper($level);

        $line = "[$datetime][$level] $message\n";

        $this->outputter->appendToFile('crawler.log', $line);
    }
}
