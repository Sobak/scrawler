<?php

namespace Tests\Functional\Matcher;

use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;

class CssSelectorTextMatcherTest extends ServerBasedTest
{
    public function testClassMatching(): void
    {
        $config = require 'css-selector-text-matcher.php';

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();

        $this->assertEquals('interesting', InMemoryResultWriter::$results[0]['match']);
    }
}
