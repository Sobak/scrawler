<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Sobak\Scrawler\Block\ClientConfigurationProvider\ClientConfigurationProviderInterface;

class ClientFactory
{
    public static function buildInstance(array $clientConfiguration): Client
    {
        $defaultConfiguration = self::getDefaultConfiguration();

        $customConfiguration = array_map(function (ClientConfigurationProviderInterface $value) {
            return $value->getConfiguration();
        }, $clientConfiguration);

        $customConfiguration = array_values($customConfiguration);

        // Fix for the  case of no client configuration providers and
        // make sure $customConfiguration will not be unpacked to null
        if ($customConfiguration === []) {
            $customConfiguration = [[]];
        }

        $configuration = array_merge_recursive($defaultConfiguration, ...$customConfiguration);

        return new Client($configuration);
    }

    protected static function getDefaultConfiguration()
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);

        return [
            'handler' => $stack,
            'http_errors' => false,
        ];
    }
}
