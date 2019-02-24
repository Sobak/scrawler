<?php

namespace Sobak\Scrawler\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class XpathMatcher extends AbstractMatcher
{
    public function match()
    {
        $response = $this->getResponse();
        $crawler = new Crawler($response->getBody()->getContents());

        $result = $crawler->filterXPath($this->getMatchBy());

        return $result->getNode(0) ? $result->getNode(0)->textContent : null;
    }
}
