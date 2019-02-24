<?php

namespace Sobak\Scrawler\Matcher;

use Symfony\Component\DomCrawler\Crawler;

class CssSelectorMatcher extends AbstractMatcher
{
    public function match()
    {
        $response = $this->getResponse();
        $crawler = new Crawler($response->getBody()->getContents());

        $result = $crawler->filter($this->getMatchBy());

        return $result->getNode(0) ? $result->getNode(0)->textContent : null;
    }
}
