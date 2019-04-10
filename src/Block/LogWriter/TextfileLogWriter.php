<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\LogWriter;

use Sobak\Scrawler\Output\Outputter;
use Sobak\Scrawler\Output\OutputWriterInterface;

class TextfileLogWriter extends AbstractLogWriter implements OutputWriterInterface
{
    /** @var Outputter */
    protected $outputter;

    public function getOutputter(): Outputter
    {
        return $this->outputter;
    }

    public function setOutputter(Outputter $outputter): void
    {
        $this->outputter = $outputter;
    }

    public function log($level, $message, array $context = array())
    {
        $datetime = date('Y-m-d H:i:s');
        $level = strtoupper($level);
        $message = $this->interpolate($message, $context);

        $line = "[$datetime][$level] $message\n";

        $this->outputter->appendToFile('crawler.log', $line);
    }
}
