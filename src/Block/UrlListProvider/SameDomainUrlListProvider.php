<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use DOMElement;
use Sobak\Scrawler\Client\Response\Elements\Url;
use Sobak\Scrawler\Client\UnsupportedProtocolException;
use Symfony\Component\DomCrawler\Crawler;

class SameDomainUrlListProvider extends AbstractUrlListProvider
{
    public function getUrls(): array
    {
        $baseDomain = $this->baseUrl->getDomain();
        $currentUrl = $this->currentUrl->getUrl();

        // Make sure that we have contents to read
        $this->response->getBody()->rewind();

        $responseCrawler = new Crawler($this->response->getBody()->getContents());
        $linkTags = $responseCrawler->filter('a')->getIterator()->getArrayCopy();
        $urls = array_map(function (DOMElement $link) use ($currentUrl) {
            try {
                return $link->hasAttribute('href') ? new Url($link->getAttribute('href'), $currentUrl) : null;
            } catch (UnsupportedProtocolException $exception) {
                return null;
            }
        }, $linkTags);

        return array_filter($urls, function (?Url $url) use ($baseDomain) {
            return $url !== null && $url->getDomain() === $baseDomain;
        });
    }
}
