<?php

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Block\LogWriter\LogWriterInterface;

class Configuration
{
    protected $baseUrl = null;

    protected $logWriters = [];

    public function addLogWriter(LogWriterInterface $logWriter)
    {
        $this->logWriters[get_class($logWriter)] = $logWriter;

        return $this;
    }

    public function getLogWriters()
    {
        return $this->logWriters;
    }

    public function removeLogWriter(string $logWriter)
    {
        unset($this->logWriters[$logWriter]);

        return $this;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }
}
