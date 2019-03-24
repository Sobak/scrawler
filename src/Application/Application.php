<?php

declare(strict_types=1);

namespace Sobak\Scrawler\Application;

use Sobak\Scrawler\Application\Commands\CrawlCommand;
use Sobak\Scrawler\Exception\ScrawlerException;
use Sobak\Scrawler\Scrawler;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Output\ConsoleOutput;

class Application
{
    protected $application;

    public function __construct()
    {
        $this->registerErrorHandler();

        $this->application = new SymfonyApplication('Scrawler', Scrawler::VERSION);
        $this->application->setCatchExceptions(false);

        $this->registerCommands();
    }

    public function run(): int
    {
        try {
            return $this->application->run();
        } catch (ScrawlerException $exception) {
            $exception->render(new ConsoleOutput());

            return -1;
        }
    }

    protected function registerErrorHandler(): void
    {
        (new \NunoMaduro\Collision\Provider)->register();
    }

    protected function registerCommands(): void
    {
        $this->application->add(new CrawlCommand());
    }
}
