<?php

namespace Sobak\Scrawler\Block\LogWriter;

use Psr\Log\AbstractLogger;

abstract class AbstractLogWriter extends AbstractLogger
{
    protected function interpolate($message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $value) {
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = $value;
            }
        }

        return strtr($message, $replace);
    }
}
