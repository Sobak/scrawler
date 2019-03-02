<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Client\Response\Elements;

class Url
{
    protected $currentUrl;

    protected $method;

    protected $rawUrl;

    protected $url;

    public function __construct(string $url, ?string $currentUrl = null, string $method = 'GET')
    {
        $this->url = $this->normalizeUrl($url, $currentUrl);
        $this->currentUrl = $currentUrl;
        $this->method = $method ? strtoupper($method) : null;
        $this->rawUrl = $url;
    }

    public function getCurrentUrl(): ?string
    {
        return $this->currentUrl;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getRawUrl(): ?string
    {
        return $this->rawUrl;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    protected function normalizeUrl($url, $currentUrl)
    {
        $url = trim($url);

        // @fixme add support before 0.1.0
        if (parse_url($url, PHP_URL_SCHEME) === null) {
            throw new \Exception('Relative URLs are not supported yet');
        }

        $url = $this->removeAnchor($url);

        if ($url === '') {
            return $currentUrl;
        }

        // Keep base URL without query string and anchor for future reference
        if ($currentUrl !== null) {
            $baseUrl = $this->removeAnchor($this->removeQueryString($currentUrl));
        } else {
            $baseUrl = '';
        }

        if ($url[0] === '?') {
            return $baseUrl . $url;
        }

        // Resolve URL with relative scheme
        if (strpos($url, '//') === 0) {
            if ($currentUrl === null) {
                throw new \Exception('First URL must provide explicit scheme (http/https)');
            }

            // Return is e.g. "http"
            $currentScheme = parse_url($currentUrl, PHP_URL_SCHEME);

            $url = $currentScheme . ':' . $url;
        }

        return $url;
    }

    protected function removeAnchor(string $url): string
    {
        if ($position = strpos($url, '#') !== false) {
            return substr($url, 0, $position);
        }

        return $url;
    }

    protected function removeQueryString(string $url): string
    {
        if ($position = strpos($url, '?') !== false) {
            return substr($url, 0, $position);
        }

        return $url;
    }
}
