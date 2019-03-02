<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Matcher;

use Sobak\Scrawler\Support\Utils;

class RegexTextMatcher extends AbstractMatcher
{
    public function match(): ?string
    {
        $responseBody = $this->getCrawler()->html();

        preg_match($this->getMatchBy(), $responseBody, $matches);

        return isset($matches['result']) ? Utils::trimWhitespace($matches['result']) : null;
    }
}
