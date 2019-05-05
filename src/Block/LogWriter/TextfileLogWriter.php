<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Block\LogWriter;

use Sobak\Scrawler\Output\OutputManagerInterface;
use Sobak\Scrawler\Output\OutputWriterInterface;

class TextfileLogWriter extends AbstractLogWriter implements OutputWriterInterface
{
    protected $filename;

    /** @var OutputManagerInterface */
    protected $outputManager;

    public function __construct(?string $filename = null)
    {
        $this->filename = $filename ?? 'crawler.log';
    }

    public function getOutputManager(): OutputManagerInterface
    {
        return $this->outputManager;
    }

    public function setOutputManager(OutputManagerInterface $outputManager): void
    {
        $this->outputManager = $outputManager;
    }

    public function log($level, $message, array $context = array())
    {
        $datetime = date('Y-m-d H:i:s');
        $level = strtoupper($level);
        $message = $this->interpolate($message, $context);

        $line = "[$datetime][$level] $message\n";

        $this->outputManager->appendToFile($this->filename, $line);
    }
}
