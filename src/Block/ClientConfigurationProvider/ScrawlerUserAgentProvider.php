<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

use Sobak\Scrawler\Scrawler;

class ScrawlerUserAgentProvider extends AbstractClientConfigurationProvider
{
    public function __construct()
    {
        $phpVersion = PHP_VERSION;
        $scrawlerVersion = Scrawler::VERSION;

        $this->setConfiguration([
            'headers' => [
                'User-Agent' => "Scrawler/{$scrawlerVersion} PHP/{$phpVersion}",
            ],
        ]);
    }
}
