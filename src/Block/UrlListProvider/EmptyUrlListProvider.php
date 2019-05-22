<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use Psr\Http\Message\ResponseInterface;
use Sobak\Scrawler\Client\Response\Elements\Url;

class EmptyUrlListProvider implements UrlListProviderInterface
{
    public function getUrls(): array
    {
        return [];
    }

    public function setBaseUrl(Url $url): void
    {
        // Do nothing...
    }

    public function setCurrentUrl(Url $url): void
    {
        // Do nothing...
    }

    public function setResponse(ResponseInterface $response): void
    {
        // Do nothing...
    }
}
