<?php

namespace Sobak\Scrawler\Support;

use Sobak\Scrawler\Block\LogWriter\LogWriterInterface;

class LogWriter implements LogWriterInterface
{
    /** @var LogWriterInterface[] */
    protected $logWriters;

    public function __construct(array $logWriters)
    {
        $this->logWriters = $logWriters;
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
