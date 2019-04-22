<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\RobotsParser;

use Sobak\Scrawler\Client\Response\Elements\Url;

interface RobotsParserInterface
{
    /**
     * RobotsParserInterface constructor.
     *
     * @param string $userAgent
     */
    public function __construct(string $userAgent = 'Scrawler');

    /**
     * Returns user agent in context of which robots.txt will be parsed.
     *
     * @return string
     */
    public function getUserAgent(): string;

    /**
     * Checks whether fetching given URL is allowed.
     *
     * @param Url $url
     * @return bool
     */
    public function isAllowed(Url $url): bool;

    /**
     * Parses robots.txt file.
     *
     * Called only once, at the beginning of the operation.
     *
     * @param string $contents
     */
    public function parseRobotsTxt(string $contents): void;
}
