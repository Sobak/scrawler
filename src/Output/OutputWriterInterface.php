<?php

namespace Sobak\Scrawler\Output;

interface OutputWriterInterface
{
    public function getOutputter();

    public function setOutputter(Outputter $outputter);
}
