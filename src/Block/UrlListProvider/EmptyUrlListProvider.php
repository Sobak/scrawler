<?php

namespace Sobak\Scrawler\Block\UrlListProvider;

use GuzzleHttp\Psr7\Response;
use Sobak\Scrawler\Client\Response\Elements\Url;

class EmptyUrlListProvider implements UrlListProviderInterface
{
    public function getUrls(): array
    {
        return [];
    }

    public function setCurrentUrl(Url $url)
    {
        // Do nothing...
    }

    public function setResponse(Response $response)
    {
        // Do nothing...
    }
}
