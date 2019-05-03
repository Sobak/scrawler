<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\Matcher;

use ArrayIterator;
use Symfony\Component\CssSelector\CssSelectorConverter;

class CssSelectorListMatcher extends AbstractMatcher implements ListMatcherInterface
{
    private static $cache;

    protected $converter;

    public function __construct(string $matchBy)
    {
        parent::__construct($matchBy);

        $this->converter = new CssSelectorConverter();
    }

    public function match(): ArrayIterator
    {
        if (isset(self::$cache[$this->getMatchBy()]) === false) {
            self::$cache[$this->getMatchBy()] = $this->converter->toXPath($this->getMatchBy());
        }

        $result = $this->getCrawler()->filterXpath(self::$cache[$this->getMatchBy()]);

        return $result->getIterator();
    }
}
