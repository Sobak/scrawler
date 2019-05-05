<?php

namespace Tests\Utils;

use Sobak\Scrawler\Block\LogWriter\AbstractLogWriter;

class InMemoryLogWriter extends AbstractLogWriter
{
    public static $log;

    public function log($level, $message, array $context = array())
    {
        $level = strtoupper($level);
        $message = $this->interpolate($message, $context);

        self::$log[] = "[{$level}] {$message}";
    }
}
