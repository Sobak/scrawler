<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use Psr\Http\Message\ResponseInterface;
use Sobak\Scrawler\Client\Response\Elements\Url;

interface UrlListProviderInterface
{
    /**
     * Returns an array of Url objects which crawler should follow next.
     *
     * @return \Sobak\Scrawler\Client\Response\Elements\Url[]
     */
    public function getUrls(): array;

    /**
     * Sets operation's base Url.
     *
     * @param Url $url
     */
    public function setBaseUrl(Url $url): void;

    /**
     * Sets the current Url as a context for finding new urls.
     *
     * @param Url $url
     */
    public function setCurrentUrl(Url $url): void;

    /**
     * Sets the current response as a contex for finding new urls.
     *
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response): void;
}
