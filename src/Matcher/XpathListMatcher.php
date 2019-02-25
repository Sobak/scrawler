<?php

namespace Sobak\Scrawler\Matcher;

use ArrayIterator;

class XpathListMatcher extends AbstractMatcher
{
    public function match(): ArrayIterator
    {
        $result = $this->getCrawler()->filterXPath($this->getMatchBy());

        return $result->getIterator();
    }
}
