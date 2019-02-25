<?php

namespace Sobak\Scrawler\Matcher;

use ArrayIterator;

class CssSelectorListMatcher extends AbstractMatcher
{
    public function match(): ArrayIterator
    {
        $result = $this->getCrawler()->filter($this->getMatchBy());

        return $result->getIterator();
    }
}
