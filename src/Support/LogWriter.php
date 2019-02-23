<?php

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

    public function debug($string)
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->debug($string);
        }
    }

    public function error($string)
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->error($string);
        }
    }

    public function info($string)
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->info($string);
        }
    }

    public function warning($string)
    {
        foreach ($this->logWriters as $logWriter) {
            $logWriter->warning($string);
        }
    }
}
