<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Matcher;

use Sobak\Scrawler\Support\Utils;

class CssSelectorTextMatcher extends AbstractMatcher
{
    public function match(): ?string
    {
        $result = $this->getCrawler()->filter($this->getMatchBy());

        return $result->count() === 0 ? null : Utils::trimWhitespace($result->text());
    }
}
