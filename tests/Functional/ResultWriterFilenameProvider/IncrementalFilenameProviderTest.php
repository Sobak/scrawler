<?php

declare(strict_types=1);

namespace Tests\Functional\ResultWriterFilenameProvider;

use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;

class IncrementalFilenameProviderTest extends ServerBasedTest
{
    public function testWithDefaultStartParameter(): void
    {
        $config = require 'config-incremental-filename.php';

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();

        $files = glob(__DIR__ . '/output/test/*.json');

        // Leave just the filenames
        $files = array_map(function ($path) {
            return str_replace(__DIR__ . '/output/test/', '', $path);
        }, $files);

        $this->assertEquals('1.json', $files[0]);
        $this->assertEquals('2.json', $files[1]);
        $this->assertEquals('3.json', $files[2]);
        $this->assertEquals('4.json', $files[3]);
    }

    public function testWithCustomStartParameter(): void
    {
        $config = require 'config-incremental-filename-start.php';

        $scrawler = new Scrawler($config, __DIR__);
        $scrawler->run();

        $files = glob(__DIR__ . '/output/test/*.json');

        // Leave just the filenames
        $files = array_map(function ($path) {
            return str_replace(__DIR__ . '/output/test/', '', $path);
        }, $files);

        $this->assertEquals('3.json', $files[0]);
        $this->assertEquals('4.json', $files[1]);
        $this->assertEquals('5.json', $files[2]);
        $this->assertEquals('6.json', $files[3]);
    }
}
