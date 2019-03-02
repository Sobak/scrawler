<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\LogWriter;

// @todo possibly change to PSR-3 interface
interface LogWriterInterface
{
    public function debug($string): void;

    public function error($string): void;

    public function info($string): void;

    public function warning($string): void;
}
