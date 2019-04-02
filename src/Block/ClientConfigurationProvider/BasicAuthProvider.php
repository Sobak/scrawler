<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\ClientConfigurationProvider;

class BasicAuthProvider extends AbstractClientConfigurationProvider
{
    public function __construct(string $username, string $password)
    {
        $this->setConfiguration([
            'auth' => [
                $username,
                $password,
                'basic'
            ],
        ]);
    }
}
