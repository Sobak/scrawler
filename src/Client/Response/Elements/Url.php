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
        $url = $this->removeAnchor(trim($url));

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

        // Resolve URL with relative protocol
        if (strpos($url, '//') === 0) {
            if ($currentUrl === null) {
                throw new \Exception('First URL must use explicit protocol (http/https)');
            }

            // Return is e.g. "http"
            $currentScheme = parse_url($currentUrl, PHP_URL_SCHEME);

            $url = $currentScheme . ':' . $url;
        }

        if ($currentUrl === null && parse_url($url, PHP_URL_SCHEME) === null) {
            throw new \Exception('First URL must be absolute');
        }

        // If the URL is absolute, we're done
        if (parse_url($url, PHP_URL_SCHEME) !== null) {
            return $url;
        }

        $baseUrl = rtrim($baseUrl, '/');

        // Resolve absolute path (but relative URL)
        if ('/' === $url[0]) {
            return $baseUrl . $url;
        }

        // Resolve relative path
        $path = parse_url(substr($currentUrl, strlen($baseUrl)), PHP_URL_PATH);
        $path = $this->canonicalizePath(substr($path, 0, (int) strrpos($path, '/')) . '/' . $url);

        return $baseUrl . ($path === '' || $path[0] !== '/' ? '/' : '') . $path;
    }

    protected function canonicalizePath($path)
    {
        if ($path === '' || $path === '/') {
            return $path;
        }

        if (substr($path, -1) === '.') {
            $path .= '/';
        }

        $output = [];
        foreach (explode('/', $path) as $segment) {
            if ($segment === '..') {
                array_pop($output);
            } elseif ($segment !== '.') {
                $output[] = $segment;
            }
        }

        return implode('/', $output);
    }

    protected function removeAnchor(string $url): string
    {
        if (($position = strpos($url, '#')) !== false) {
            return substr($url, 0, $position);
        }

        return $url;
    }

    protected function removeQueryString(string $url): string
    {
        if (($position = strpos($url, '?')) !== false) {
            return substr($url, 0, $position);
        }

        return $url;
    }
}
