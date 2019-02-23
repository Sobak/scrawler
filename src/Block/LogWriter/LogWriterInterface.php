<?php

namespace Sobak\Scrawler\Block\LogWriter;

// @todo possibly change to PSR-3 interface
interface LogWriterInterface
{
    public function debug($string);

    public function error($string);

    public function info($string);

    public function warning($string);
}
