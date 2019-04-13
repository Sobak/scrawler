<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Support;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

class LogWriter implements LoggerInterface
{
    /** @var array */
    protected $logWriters;

    public function __construct(array $logWriters, Outputter $outputter)
    {
        $this->logWriters = $logWriters;

        // Set Outputter for log writers that require it
        foreach ($this->logWriters as $logWriter) {
            if ($logWriter['class'] instanceof OutputWriterInterface) {
                $logWriter['class']->setOutputter($outputter);
            }
        }
    }

    public function emergency($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::EMERGENCY)) {
                $logWriter->emergency($message, $context);
            }
        }
    }

    public function alert($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::ALERT)) {
                $logWriter->alert($message, $context);
            }
        }
    }

    public function critical($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::CRITICAL)) {
                $logWriter->critical($message, $context);
            }
        }
    }

    public function error($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::ERROR)) {
                $logWriter->error($message, $context);
            }
        }
    }

    public function warning($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::WARNING)) {
                $logWriter->warning($message, $context);
            }
        }
    }

    public function notice($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::NOTICE)) {
                $logWriter->notice($message, $context);
            }
        }
    }

    public function info($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::INFO)) {
                $logWriter->info($message, $context);
            }
        }
    }

    public function debug($message, array $context = [])
    {
        foreach ($this->logWriters as $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, LogLevel::DEBUG)) {
                $logWriter->debug($message, $context);
            }
        }
    }

    public function log($level, $message, array $context = [])
    {
        foreach ($this->logWriters as $verbosity => $logWriter) {
            $verbosity = $logWriter['verbosity'];
            /** @var LoggerInterface $logWriter */
            $logWriter = $logWriter['class'];

            if ($this->shouldLog($verbosity, $level)) {
                $logWriter->log($level, $message, $context);
            }
        }
    }

    protected function shouldLog($verbosity, $messageLevel)
    {
        $levels = [
            LogLevel::EMERGENCY => 8,
            LogLevel::ALERT => 7,
            LogLevel::CRITICAL => 6,
            LogLevel::ERROR => 5,
            LogLevel::WARNING => 4,
            LogLevel::NOTICE => 3,
            LogLevel::INFO => 2,
            LogLevel::DEBUG => 1,
        ];

        return $levels[$messageLevel] >= $levels[$verbosity];
    }
}
