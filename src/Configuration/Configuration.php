<?php

namespace Sobak\Scrawler\Configuration;

class Configuration
{
    protected $baseUrl;

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
