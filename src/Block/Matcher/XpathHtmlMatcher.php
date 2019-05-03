<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\Matcher;

use Sobak\Scrawler\Support\Utils;

class XpathHtmlMatcher extends AbstractMatcher implements SingleMatcherInterface
{
    public function match(): ?string
    {
        $result = $this->getCrawler()->filterXPath($this->getMatchBy());

        return $result->count() === 0 ? null : Utils::trimWhitespace($result->html());
    }
}
