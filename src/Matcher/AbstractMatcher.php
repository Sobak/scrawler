<?php

namespace Sobak\Scrawler\Matcher;

use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractMatcher implements MatcherInterface
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

    public function setCrawler(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getMatchBy(): string
    {
        return $this->matchBy;
    }
}
