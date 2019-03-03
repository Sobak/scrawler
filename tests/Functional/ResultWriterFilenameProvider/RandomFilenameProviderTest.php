<?php

declare(strict_types=1);

namespace Tests\Functional\ResultWriterFilenameProvider;

use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;

class RandomFilenameProviderTest extends ServerBasedTest
{
    public function testFilenameProviderForJsonFileResultWriter(): void
    {
        $scrawler = new Scrawler(__DIR__ . '/config-random-filename.php');
        $scrawler->run();

        $files = glob(__DIR__ . '/output/test/*.json');

        // This test may file when RandomFilenameProvider will generate
        // not that unique names... But so may the real-life case
        $this->assertEquals(4, count($files));
    }
}
