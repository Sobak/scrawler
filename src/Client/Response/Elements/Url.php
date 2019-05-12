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
        $this->method = strtoupper($method);
        $this->rawUrl = $url;
    }

    public function getCurrentUrl(): ?string
    {
        return $this->currentUrl;
    }

    public function getDomain(?string $url = null): string
    {
        return $this->extractDomain($url ?? $this->url);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRawUrl(): string
    {
        return $this->rawUrl;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->url;
    }

    protected function extractDomain(string $url): string
    {
        $components = parse_url($url);
        $domain = '';

        if (isset($components['scheme']) === false) {
            throw new \Exception('Cannot get domain from protocol-relative URL');
        }

        $domain .= $components['scheme'] . '://';

        if (isset($components['user'])) {
            $domain .= "{$components['user']}:";
        }
        if (isset($components['pass'])) {
            $domain .= "{$components['pass']}@";
        }

        $domain .= $components['host'];

        if (isset($components['port'])) {
            $domain .= ":{$components['port']}";
        }

        return $domain;
    }

    protected function normalizeUrl(string $url, ?string $currentUrl)
    {
        if ($currentUrl === null) {
            $this->checkCurrentUrl($url);
        }

        // Resolve URL with relative protocol
        if (strpos($url, '//') === 0) {
            $currentScheme = parse_url($currentUrl, PHP_URL_SCHEME);
            $url = $currentScheme . ':' . $url;
        }

        $url = $this->removeAnchor(trim($url));

        if ($url === '') {
            return $currentUrl;
        }

        if ($url[0] === '?') {
            return rtrim($this->removeAnchor($this->removeQueryString($currentUrl)), '/') . $url;
        }

        // If the URL is absolute, we're done
        if (parse_url($url, PHP_URL_SCHEME) !== null) {
            return $url;
        }

        return $this->resolveRelativePath($url, $currentUrl);
    }

    protected function resolveRelativePath(string $path, string $currentUrl): string
    {
        if ($path[0] === '/') {
            // URL starts from the root domain but we cannonicalize the path
            // to get rid of potential dots (. or ..) meaning nothing here
            return $this->getDomain($currentUrl) . '/' . $this->cannonicalizePath($path);
        }

        $currentUrlSegments = explode('/', rtrim($currentUrl, '/'));

        // If there is no path attatched to the current URL
        // we only need to append new path to it and return
        if (count($currentUrlSegments) === 3) {
            return implode('/', $currentUrlSegments) . '/' . $path;
        }

        $currentUrlPath = ltrim((string) parse_url($currentUrl, PHP_URL_PATH), '/');

        // If the current URL does not end with a slash it means it is a file name
        // and that name needs to be dropped and further replaced with new path
        if (substr($currentUrlPath, -1) !== '/') {
            $currentUrlPath = implode('/', explode('/', $currentUrlPath, -1));
        }

        $cannonicalizedPath = $this->cannonicalizePath($currentUrlPath . '/' . $path);

        return $this->getDomain($currentUrl) . '/' . $cannonicalizedPath;
    }

    public function cannonicalizePath(string $path): string
    {
        $segments = explode('/', $path);
        $result = [];

        foreach ($segments as $segment) {
            switch ($segment) {
                case '':
                case '.':
                    break;

                case '..':
                    array_pop($result);
                    break;

                default:
                    $result[] = $segment;
                    break;
            }
        }

        $path = implode('/', $result) . (substr($path, -1) === '/' ? '/' : '');

        return $path === '/' ? '' : $path;
    }

    protected function checkCurrentUrl($url): void
    {
        if (parse_url($url, PHP_URL_SCHEME) === null) {
            throw new \Exception('First URL must be absolute');
        }

        if (in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https']) === false) {
            throw new \Exception('Only http and https protocols are supported');
        }
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
