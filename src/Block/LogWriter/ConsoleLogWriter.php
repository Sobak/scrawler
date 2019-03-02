<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\LogWriter;

use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleLogWriter implements LogWriterInterface
{
    protected $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    public function debug($string): void
    {
        $this->output->writeln($string);
    }

    public function error($string): void
    {
        $this->output->writeln("<error>$string</error>");
    }

    public function info($string): void
    {
        $this->output->writeln("<info>$string</info>");
    }

    public function warning($string): void
    {
        $this->output->writeln("<comment>$string</comment>");
    }
}
