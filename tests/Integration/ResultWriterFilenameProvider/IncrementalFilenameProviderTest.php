<?php

declare(strict_types=1);

namespace Tests\Integration\ResultWriterFilenameProvider;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\IncrementalFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\PostEntity;

/**
 * @covers \Sobak\Scrawler\Block\ResultWriter\AbstractResultWriter
 * @covers \Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter
 * @covers \Sobak\Scrawler\Block\ResultWriter\FilenameProvider\IncrementalFilenameProvider
 * @covers \Sobak\Scrawler\Output\OutputManager
 * @covers \Sobak\Scrawler\Scrawler
 */
class IncrementalFilenameProviderTest extends IntegrationTest
{
    public function testWithDefaultStartParameter(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(IntegrationTest::getHostUrl() . '/posts.html')
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('content', new CssSelectorHtmlMatcher('span.content'))
                    ->addFieldDefinition('title', new CssSelectorHtmlMatcher('h2'))
                    ->addEntityMapping(PostEntity::class)
                    ->addResultWriter(PostEntity::class, new JsonFileResultWriter([
                        'filename' => new IncrementalFilenameProvider(),
                    ]))
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
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
            ->setBaseUrl(IntegrationTest::getHostUrl() . '/posts.html')
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('content', new CssSelectorHtmlMatcher('span.content'))
                    ->addFieldDefinition('title', new CssSelectorHtmlMatcher('h2'))
                    ->addEntityMapping(PostEntity::class)
                    ->addResultWriter(PostEntity::class, new JsonFileResultWriter([
                        'filename' => new IncrementalFilenameProvider([
                            'start' => 3,
                        ]),
                    ]))
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
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
