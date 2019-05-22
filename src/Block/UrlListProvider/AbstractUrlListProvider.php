<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use Psr\Http\Message\ResponseInterface;
use Sobak\Scrawler\Client\Response\Elements\Url;

abstract class AbstractUrlListProvider implements UrlListProviderInterface
{
    /** @var Url */
    protected $baseUrl;

    /** @var Url */
    protected $currentUrl;

    /** @var ResponseInterface */
    protected $response;

    public function setBaseUrl(Url $url): void
    {
        $this->baseUrl = $url;
    }

    public function setCurrentUrl(Url $url): void
    {
        $this->currentUrl = $url;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
