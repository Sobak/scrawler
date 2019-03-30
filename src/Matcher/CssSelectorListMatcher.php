<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Matcher;

use ArrayIterator;

class CssSelectorListMatcher extends AbstractMatcher implements ListMatcherInterface
{
    public function match(): ArrayIterator
    {
        $result = $this->getCrawler()->filter($this->getMatchBy());

        return $result->getIterator();
    }
}
