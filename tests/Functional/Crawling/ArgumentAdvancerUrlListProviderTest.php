<?php

declare(strict_types=1);

namespace Tests\Functional\Crawling;

use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;

class ArgumentAdvancerUrlListProviderTest extends ServerBasedTest
{
    public function testWithStopArgument(): void
    {
        $config = require 'config-start-stop.php';

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Second message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[2]['match']);
        $this->assertEquals('Fourth message', InMemoryResultWriter::$results[3]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[4]['match']);
    }

    public function testWithNonDefaultStep(): void
    {
        $config = require 'config-step-2.php';

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[2]['match']);
    }
}
