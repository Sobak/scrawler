<?php

declare(strict_types=1);

namespace Tests\Functional\ResultWriterFilenameProvider;

use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\IncrementalFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\PostEntity;

class IncrementalFilenameProviderTest extends ServerBasedTest
{
    public function testWithDefaultStartParameter(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl() . '/posts.html')
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('content', new CssSelectorTextMatcher('span.content'))
                    ->addFieldDefinition('title', new CssSelectorTextMatcher('h2'))
                    ->addEntityMapping(PostEntity::class)
                    ->addResultWriter(PostEntity::class, new JsonFileResultWriter([
                        'filename' => new IncrementalFilenameProvider(),
                    ]))
                ;
            })
        ;

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
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl() . '/posts.html')
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('content', new CssSelectorTextMatcher('span.content'))
                    ->addFieldDefinition('title', new CssSelectorTextMatcher('h2'))
                    ->addEntityMapping(PostEntity::class)
                    ->addResultWriter(PostEntity::class, new JsonFileResultWriter([
                        'filename' => new IncrementalFilenameProvider([
                            'start' => 3,
                        ]),
                    ]))
                ;
            })
        ;

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
