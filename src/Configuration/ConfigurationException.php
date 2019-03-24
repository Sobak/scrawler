<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Configuration;

use Sobak\Scrawler\Exception\ScrawlerException;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurationException extends ScrawlerException
{
    public function render(OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<error> Error </error> <comment>Configuration problem found:</comment>');
        $output->writeln($this->getMessage());
        $output->writeln('');
        $output->writeln('<comment>Please consult the docs on configuration to learn more</comment>');
    }
}
