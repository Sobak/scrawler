<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Output;

interface OutputWriterInterface
{
    public function getOutputter(): Outputter;

    public function setOutputter(Outputter $outputter): void;
}
