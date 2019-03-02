<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Support;

use Sobak\Scrawler\Block\LogWriter\LogWriterInterface;
use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

class LogWriter implements LogWriterInterface
{
    /** @var LogWriterInterface[] */
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

    public function debug($string): void
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->debug($string);
        }
    }

    public function error($string): void
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->error($string);
        }
    }

    public function info($string): void
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->info($string);
        }
    }

    public function warning($string): void
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->warning($string);
        }
    }
}
