<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use Sobak\Scrawler\Client\Response\Elements\Url;
use Sobak\Scrawler\Client\Response\StatusCode;

class ArgumentAdvancerUrlListProvider extends AbstractUrlListProvider
{
    protected $current;

    protected $start;

    protected $step;

    protected $stop;

    protected $template;

    public function __construct(string $template, int $start = 1, int $step = 1, ?int $stop = null)
    {
        $this->template = $template;
        $this->start = $start;
        $this->step = $step;
        $this->stop = $stop;
        $this->current = $start;
    }

    public function getUrls(): array
    {
        $template = $this->template;
        if ($this->isRelative($template)) {
            $template = $this->baseUrl->getUrl() . '/' . ltrim($template, '/');
        }

        $nextUrl = sprintf($template, $this->current);

        if (
            ($this->stop !== null && $this->current > $this->stop)
            || $this->response->getStatusCode() === StatusCode::HTTP_NOT_FOUND
        ) {
            return [];
        }

        $this->current += $this->step;

        return [
            new Url($nextUrl, $this->currentUrl->getUrl()),
        ];
    }

    protected function isRelative($url): bool
    {
        return parse_url($url, PHP_URL_SCHEME) === null;
    }
}
