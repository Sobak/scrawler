<?php

declare(strict_types=1);

namespace Tests\Functional\ResultWriterFilenameProvider;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\EntityPropertyFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\PostEntity;

/**
 * @covers \Sobak\Scrawler\Block\ResultWriter\AbstractResultWriter
 * @covers \Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter
 * @covers \Sobak\Scrawler\Block\ResultWriter\FilenameProvider\EntityPropertyFilenameProvider
 * @covers \Sobak\Scrawler\Output\Outputter
 * @covers \Sobak\Scrawler\Scrawler
 */
class EntityPropertyFilenameProviderTest extends ServerBasedTest
{
    public function testFilenameProviderForJsonFileResultWriter(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl() . '/posts.html')
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('content', new CssSelectorHtmlMatcher('span.content'))
                    ->addFieldDefinition('title', new CssSelectorHtmlMatcher('h2'))
                    ->addEntityMapping(PostEntity::class)
                    ->addResultWriter(PostEntity::class, new JsonFileResultWriter([
                        'filename' => new EntityPropertyFilenameProvider([
                            'property' => 'title',
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

        $this->assertEquals('Cuatro.json', $files[0]);
        $this->assertEquals('Dos.json', $files[1]);
        $this->assertEquals('Tres.json', $files[2]);
        $this->assertEquals('Uno.json', $files[3]);
    }
}
