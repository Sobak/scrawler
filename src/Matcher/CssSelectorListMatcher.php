<?php

namespace Sobak\Scrawler\Matcher;

use ArrayIterator;

class CssSelectorListMatcher extends AbstractMatcher
{
    // @todo correct return type?
    public function match(): ArrayIterator
    {
        $result = $this->getCrawler()->filter($this->getMatchBy());

        return $result->getIterator();
    }
}
