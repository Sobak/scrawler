<?php

namespace Tests\Utils;

use Sobak\Scrawler\Block\UrlListProvider\EmptyUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;

class BasicConfigurationProvider
{
    public static function getConfiguration(): Configuration
    {
        return (new Configuration())
            ->setOperationName('test')
            ->addUrlListProvider(new EmptyUrlListProvider())
        ;
    }
}
