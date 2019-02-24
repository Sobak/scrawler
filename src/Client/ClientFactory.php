<?php

namespace Sobak\Scrawler\Client;

use GuzzleHttp\Client;

class ClientFactory
{
    public static function applyCustomConfiguration(array $clientConfiguration): Client
    {
        $customConfiguration = array_map(function ($value) {
            return $value->getConfiguration();
        }, $clientConfiguration);

        $customConfiguration = array_values($customConfiguration);

        // Fix for the  case of no client configuration providers and
        // make sure $customConfiguration will not be unpacked to null
        if ($customConfiguration === []) {
            $customConfiguration = [[]];
        }

        $customConfiguration = array_merge_recursive(...$customConfiguration);

        return new Client($customConfiguration);
    }
}
