<?php

namespace Sobak\Scrawler\Client\Response;

use function GuzzleHttp\Psr7\parse_header;

class ContentType
{
    protected $contentType;

    protected $processableTypes = [
        'application/xhtml+xml',
        'application/xml',
        'text/html',
        'text/xml',
    ];

    public function __construct($contentType)
    {
        $this->contentType = parse_header($contentType)[0][0];
    }

    public function getType()
    {
        return $this->contentType;
    }

    public function isProcessable(): bool
    {
        return in_array($this->contentType, $this->processableTypes);
    }
}
