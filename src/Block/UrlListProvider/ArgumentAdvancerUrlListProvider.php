<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\UrlListProvider;

use Sobak\Scrawler\Client\Response\Elements\Url;

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
        $nextUrl = sprintf($this->template, $this->current);

        if ($this->current > $this->stop) {
            return [];
        }

        $this->current += $this->step;

        return [
            new Url($nextUrl, $this->currentUrl->getUrl()),
        ];
    }
}
