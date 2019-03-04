<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\LogWriter;

// @todo possibly change to PSR-3 interface
interface LogWriterInterface
{
    /**
     * Broadcasts a debug message.
     *
     * @param string $string
     */
    public function debug(string $string): void;

    /**
     * Broadcasts an error message.
     *
     * @param string $string
     */
    public function error(string $string): void;

    /**
     * Broadcasts an informational message.
     *
     * @param string $string
     */
    public function info(string $string): void;

    /**
     * Broadcasts a warning message.
     *
     * @param string $string
     */
    public function warning(string $string): void;
}
