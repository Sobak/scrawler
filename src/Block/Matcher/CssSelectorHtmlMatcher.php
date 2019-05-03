<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\Matcher;

use Sobak\Scrawler\Support\Utils;
use Symfony\Component\CssSelector\CssSelectorConverter;

class CssSelectorHtmlMatcher extends AbstractMatcher implements SingleMatcherInterface
{
    private static $cache;

    protected $converter;

    public function __construct(string $matchBy)
    {
        parent::__construct($matchBy);

        $this->converter = new CssSelectorConverter();
    }

    public function match(): ?string
    {
        if (isset(self::$cache[$this->getMatchBy()]) === false) {
            self::$cache[$this->getMatchBy()] = $this->converter->toXPath($this->getMatchBy());
        }

        $result = $this->getCrawler()->filterXPath(self::$cache[$this->getMatchBy()]);

        return $result->count() === 0 ? null : Utils::trimWhitespace($result->html());
    }
}
