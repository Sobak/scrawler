<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\LogWriter;

use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleLogWriter extends AbstractLogWriter
{
    protected $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    public function log($level, $message, array $context = [])
    {
        $message = $this->interpolate($message, $context);

        $this->output->writeln($this->styleMessageForLevel($message, $level));
    }

    protected function styleMessageForLevel($message, $level)
    {
        $style = $this->getStyleForLevel($level);

        return sprintf($style, $message);
    }

    protected function getStyleForLevel($level)
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::ERROR:
                return '<error>%s</error>';
            case LogLevel::WARNING:
                return '<comment>%s</comment>';
            case LogLevel::NOTICE:
                return '<info>%s</info>';
            case LogLevel::DEBUG:
                return "\e[1;30m%s\e[0m";
            default:
                return '%s';
        }
    }
}
