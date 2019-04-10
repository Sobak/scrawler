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
        $style = $this->getStyleForLevel($level);

        if ($style !== null) {
            $this->output->writeln("<{$style}>{$message}</{$style}>");
            return;
        }

        $this->output->writeln($message);
    }

    protected function getStyleForLevel($level)
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::ERROR:
                return 'error';
            case LogLevel::WARNING:
                return 'comment';
            case LogLevel::NOTICE:
            case LogLevel::INFO:
                return 'info';
            case LogLevel::DEBUG:
                return null;
            default:
                throw new \Exception('Unsupported logger level: ' . (string) $level);
        }
    }
}
