<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use Sobak\Scrawler\Client\Response\Elements\Url;
use Sobak\Scrawler\Client\UnsupportedProtocolException;

class ArrayUrlListProvider extends AbstractUrlListProvider
{
    protected $urls;

    public function __construct(array $urls)
    {
        $this->urls = $urls;
    }

    public function getUrls(): array
    {
        $currentUrl = $this->currentUrl->getUrl();

        return array_filter(array_map(function (string $url) use ($currentUrl) {
            try {
                return new Url($url, $currentUrl);
            } catch (UnsupportedProtocolException $exception) {
                return null;
            }
        }, $this->urls));
    }
}
