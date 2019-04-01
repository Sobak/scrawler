<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

use Sobak\Scrawler\Scrawler;

class ScrawlerUserAgentProvider extends AbstractClientConfigurationProvider
{
    public function __construct(string $botName, string $botUrl)
    {
        $userAgent = sprintf(
            '%s (<%s>; powered by Scrawler %s, %s)',
            $botName,
            $botUrl,
            Scrawler::VERSION,
            'http://scrawler.sobak.pl'
        );

        $this->setConfiguration([
            'headers' => [
                'User-Agent' => $userAgent,
            ],
        ]);
    }
}
