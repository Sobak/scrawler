<?php

declare(strict_types=1);

namespace Tests\Functional\ResultWriterFilenameProvider;

use Sobak\Scrawler\Block\ResultWriter\FilenameProvider\RandomFilenameProvider;
use Sobak\Scrawler\Block\ResultWriter\JsonFileResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\PostEntity;

class RandomFilenameProviderTest extends ServerBasedTest
{
    public function testFilenameProviderForJsonFileResultWriter(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl() . '/posts.html')
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.post'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('content', new CssSelectorTextMatcher('span.content'))
                    ->addFieldDefinition('title', new CssSelectorTextMatcher('h2'))
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
