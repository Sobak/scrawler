<?php

namespace Tests\Functional\Matcher;

use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;

class CssSelectorTextMatcherTest extends ServerBasedTest
{
    public function testClassMatching()
    {
        $scrawler = new Scrawler(__DIR__ . '/css-selector-text-matcher.php');
        $scrawler->run();

        $this->assertEquals('interesting', InMemoryResultWriter::$results[0]['match']);
    }
}
