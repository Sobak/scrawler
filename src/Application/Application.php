<?php

namespace Sobak\Scrawler\Application;

use Sobak\Scrawler\Application\Commands\CrawlCommand;
use Sobak\Scrawler\Scrawler;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application
{
    protected $application;

    public function __construct()
    {
        $this->application = new SymfonyApplication('Scrawler', Scrawler::VERSION);

        $this->registerCommands();
    }

    public function run()
    {
        return $this->application->run();
    }

    protected function registerCommands()
    {
        $this->application->add(new CrawlCommand());
    }
}
