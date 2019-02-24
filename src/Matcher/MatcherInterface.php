<?php

namespace Sobak\Scrawler\Matcher;

use GuzzleHttp\Psr7\Response;

interface MatcherInterface
{
    public function __construct(string $matchBy);

    public function getMatchBy(): string;

    public function getResponse(): Response;

    public function setResponse(Response $response);

    public function match();
}
