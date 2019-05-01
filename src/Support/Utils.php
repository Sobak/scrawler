<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Support;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Utils
{
    public static function removeDirectoryRecursively(string $directoryPath): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directoryPath, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach($iterator as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        rmdir($directoryPath);
    }

    public static function slugify(string $string, string $separator = '-'): string
    {
        // Remove all non-ASCII characters
        $slug = preg_replace('/[^\x20-\x7E]/u', '', $string);

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';
        $slug = preg_replace('!['.preg_quote($flip).']+!u', $separator, $slug);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $slug = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', strtolower($slug));

        // Replace all separator characters and whitespace by a single separator
        $slug = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $slug);

        return trim($slug, $separator);
    }

    public static function trimWhitespace(string $string): string
    {
        return trim($string);
    }
}
