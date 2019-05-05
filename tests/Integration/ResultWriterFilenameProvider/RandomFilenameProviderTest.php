<?php

declare(strict_types=1);

namespace Tests\Integration\ResultWriterFilenameProvider;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\RandomFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\PostEntity;

/**
 * @covers \Sobak\Scrawler\Block\ResultWriter\AbstractResultWriter
 * @covers \Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter
 * @covers \Sobak\Scrawler\Block\ResultWriter\FilenameProvider\RandomFilenameProvider
 * @covers \Sobak\Scrawler\Output\Outputter
 * @covers \Sobak\Scrawler\Scrawler
 */
class RandomFilenameProviderTest extends IntegrationTest
{
    public function testFilenameProviderForJsonFileResultWriter(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(IntegrationTest::getHostUrl() . '/posts.html')
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('content', new CssSelectorHtmlMatcher('span.content'))
                    ->addFieldDefinition('title', new CssSelectorHtmlMatcher('h2'))
                    ->addEntityMapping(PostEntity::class)
                    ->addResultWriter(PostEntity::class, new JsonFileResultWriter([
                        'filename' => new RandomFilenameProvider(),
                    ]))
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $files = glob(__DIR__ . '/output/test/*.json');

        // This test may file when RandomFilenameProvider will generate
        // not that unique names... But so may the real-life case
        $this->assertEquals(4, count($files));
    }
}
