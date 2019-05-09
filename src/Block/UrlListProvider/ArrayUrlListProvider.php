<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use Sobak\Scrawler\Client\Response\Elements\Url;

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

        return array_map(function (string $url) use ($currentUrl) {
            return new Url($url, $currentUrl);
        }, $this->urls);
    }
}
