<?php

namespace Tests\Functional\Matcher;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\SimpleMatchEntity;

class ResultWriterTest extends ServerBasedTest
{
    public function testMultipleResultWritersPerEntity(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl())
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('span.match'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter(['group' => 'first']))
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter(['group' => 'second']))
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertEquals('interesting', InMemoryResultWriter::$results['first'][0]['match']);
        $this->assertEquals(InMemoryResultWriter::$results['first'], InMemoryResultWriter::$results['second']);
    }
}
