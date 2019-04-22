<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\RobotsParser;

use RobotsTxtParser\RobotsTxtParser;
use RobotsTxtParser\RobotsTxtValidator;
use Sobak\Scrawler\Client\Response\Elements\Url;

class DefaultRobotsParser extends AbstractRobotsParser
{
    protected $rules;

    public function isAllowed(Url $url): bool
    {
        $validator = new RobotsTxtValidator($this->rules);

        return $validator->isUrlAllow($url->getUrl(), $this->getUserAgent());
    }

    public function parseRobotsTxt(string $contents): void
    {
        // Change line endings to PHP_EOL due to inflexible handling in 3rd-party library
        $contents = preg_replace('#\R#u', PHP_EOL, $contents);

        $parser = new RobotsTxtParser($contents);
        $this->rules = $parser->getRules();
    }
}
