<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\Matcher;

use Symfony\Component\DomCrawler\Crawler;

interface MatcherInterface
{
    public function __construct(string $matchBy);

    public function getCrawler(): Crawler;

    public function setCrawler(Crawler $crawler): void;

    public function getMatchBy(): string;

    public function match();
}
