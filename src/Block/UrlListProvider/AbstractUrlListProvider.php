<?php

namespace Sobak\Scrawler\Block\UrlListProvider;

use GuzzleHttp\Psr7\Response;
use Sobak\Scrawler\Client\Response\Elements\Url;

abstract class AbstractUrlListProvider implements UrlListProviderInterface
{
    /** @var Url */
    protected $currentUrl;

    /** @var Response */
    protected $response;

    public function setCurrentUrl(Url $url)
    {
        $this->currentUrl = $url;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
