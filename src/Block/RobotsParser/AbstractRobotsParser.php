<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\RobotsParser;

abstract class AbstractRobotsParser implements RobotsParserInterface
{
    protected $userAgent;

    public function __construct(string $userAgent = 'Scrawler')
    {
        $this->userAgent = $userAgent;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}
