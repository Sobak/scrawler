<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Matcher;

use ArrayIterator;

interface ListMatcherInterface extends MatcherInterface
{
    public function match(): ArrayIterator;
}
