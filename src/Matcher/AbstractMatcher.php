<?php

namespace Sobak\Scrawler\Matcher;

use GuzzleHttp\Psr7\Response;

abstract class AbstractMatcher implements MatcherInterface
{
    protected $matchBy;

    protected $response;

    public function __construct(string $matchBy)
    {
        $this->matchBy = $matchBy;
    }

    public function getMatchBy(): string
    {
        return $this->matchBy;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
