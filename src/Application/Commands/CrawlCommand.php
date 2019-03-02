<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Application\Commands;

use Sobak\Scrawler\Scrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('crawl');
        $this->setDescription('Runs the crawler with specified configuration');
        $this->addArgument('config', InputArgument::REQUIRED, 'Path to the configuration file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configPath = $input->getArgument('config');

        $scrawler = new Scrawler($configPath);

        return $scrawler->run();
    }
}
