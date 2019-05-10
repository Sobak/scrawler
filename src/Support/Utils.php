<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Support;

class Utils
{
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

        $slug = trim($slug, $separator);

        // Last restort for slugs which turned out to be empty (having no ASCII characters)
        if (empty($slug)) {
            return uniqid();
        }

        return $slug;
    }

    public static function trimWhitespace(string $string): string
    {
        return trim($string);
    }
}
