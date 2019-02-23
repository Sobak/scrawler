<?php

namespace Sobak\Scrawler\Application;

use Sobak\Scrawler\Scrawler;
use Symfony\Component\Console\Application as SymfonyApplication;

class Application
{
    protected $application;

    public function __construct()
    {
        $this->application = new SymfonyApplication('Scrawler', Scrawler::VERSION);
    }

    public function run()
    {
        return $this->application->run();
    }
}
