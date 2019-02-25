<?php

namespace Sobak\Scrawler\Matcher;

use Symfony\Component\DomCrawler\Crawler;

interface MatcherInterface
{
    public function __construct(string $matchBy);

    public function getCrawler(): Crawler;

    public function setCrawler(Crawler $crawler);

    public function getMatchBy(): string;

    public function match();
}
