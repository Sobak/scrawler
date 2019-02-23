<?php

namespace Sobak\Scrawler\Block\LogWriter;

use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleLogWriter implements LogWriterInterface
{
    protected $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    public function debug($string)
    {
        $this->output->writeln($string);
    }

    public function error($string)
    {
        $this->output->writeln("<error>$string</error>");
    }

    public function info($string)
    {
        $this->output->writeln("<info>$string</info>");
    }

    public function warning($string)
    {
        $this->output->writeln("<comment>$string</comment>");
    }
}
