<?php

declare(strict_types=1);

namespace Tests\Functional\ResultWriterFilenameProvider;

use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;

class EntityPropertyFilenameProviderTest extends ServerBasedTest
{
    public function testFilenameProviderForJsonFileResultWriter(): void
    {
        $scrawler = new Scrawler(__DIR__ . '/config-entity-property-filename.php');
        $scrawler->run();

        $files = glob(__DIR__ . '/output/test/*.json');

        // Leave just the filenames
        $files = array_map(function ($path) {
            return str_replace(__DIR__ . '/output/test/', '', $path);
        }, $files);

        $this->assertEquals('Cuatro.json', $files[0]);
        $this->assertEquals('Dos.json', $files[1]);
        $this->assertEquals('Tres.json', $files[2]);
        $this->assertEquals('Uno.json', $files[3]);
    }
}
