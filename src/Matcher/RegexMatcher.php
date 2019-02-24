<?php

namespace Sobak\Scrawler\Matcher;

class RegexMatcher extends AbstractMatcher
{
    public function match()
    {
        $responseBody = $this->getResponse()->getBody()->getContents();

        preg_match($this->getMatchBy(), $responseBody, $matches);

        return isset($matches['result']) ? $matches['result'] : null;
    }
}
