<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Application\Commands;

use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Output\OutputManager;
use Sobak\Scrawler\Scrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CrawlCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('crawl');
        $this->setDescription('Runs the crawler with specified configuration');
        $this->addArgument('config', InputArgument::REQUIRED, 'Path to the configuration file');
        $this->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Do not ask on potential directory override', false);
        $this->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Path to the output directory, created next to config file by default');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configurationPath = $input->getArgument('config');
        $outputPath = $input->getOption('output');

        if ($outputPath === null) {
            $outputPath = dirname(realpath($configurationPath)) . '/output';
        }

        if (is_file($configurationPath) === false) {
            throw new \Exception("Could not find configuration at '{$configurationPath}'");
        }

        if (is_dir($outputPath) && $input->getOption('force') === false) {
            $helper = $this->getHelper('question');
            $confirmation = new ConfirmationQuestion('Directory already exists, do you want to remove it (will most probably override old results too)? [Y/n] ');

            if ($helper->ask($input, $output, $confirmation) === false) {
                return 0;
            }
        }

        /** @noinspection PhpIncludeInspection */
        $configuration = require $configurationPath;

        if (($configuration instanceof Configuration) === false) {
            throw new \Exception('Application must return the Configuration instance');
        }

//        $outputManager = new OutputManager($outputPath)

        $scrawler = new Scrawler($configuration, $outputPath);

        return $scrawler->run();
    }
}
