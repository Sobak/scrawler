<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Support;

use Psr\Log\LoggerInterface;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

class LogWriter implements LoggerInterface
{
    /** @var LoggerInterface[] */
    protected $logWriters;

    public function __construct(array $logWriters, Outputter $outputter)
    {
        $this->logWriters = $logWriters;

        // Set Outputter for log writers that require it
        foreach ($this->logWriters as $logWriter) {
            if ($logWriter instanceof OutputWriterInterface) {
                $logWriter->setOutputter($outputter);
            }
        }
    }

    public function emergency($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->emergency($message, $context);
        }
    }

    public function alert($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->alert($message, $context);
        }
    }

    public function critical($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->critical($message, $context);
        }
    }

    public function error($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->error($message, $context);
        }
    }

    public function warning($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->warning($message, $context);
        }
    }

    public function notice($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->notice($message, $context);
        }
    }

    public function info($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->info($message, $context);
        }
    }

    public function debug($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->debug($message, $context);
        }
    }

    public function log($level, $message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->log($level, $message, $context);
        }
    }
}
