<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\Matcher;

use ArrayIterator;

class XpathListMatcher extends AbstractMatcher implements ListMatcherInterface
{
    public function match(): ArrayIterator
    {
        $result = $this->getCrawler()->filterXPath($this->getMatchBy());

        return $result->getIterator();
    }
}
