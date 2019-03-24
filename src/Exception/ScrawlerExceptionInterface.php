<?php

namespace Sobak\Scrawler\Exception;

use Symfony\Component\Console\Output\OutputInterface;

interface ScrawlerExceptionInterface
{
    public function render(OutputInterface $output);
}
