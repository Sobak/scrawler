<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\Matcher;

use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractMatcher
{
    protected $crawler;

    protected $matchBy;

    public function __construct(string $matchBy)
    {
        $this->matchBy = $matchBy;
    }

    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    public function setCrawler(Crawler $crawler): void
    {
        $this->crawler = $crawler;
    }

    public function getMatchBy(): string
    {
        return $this->matchBy;
    }
}
