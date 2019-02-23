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

    public function info($string)
    {
        $this->output->writeln("<info>$string</info>");
    }
}
