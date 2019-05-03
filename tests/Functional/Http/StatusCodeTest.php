<?php

namespace Tests\Functional\Http;

use Sobak\Scrawler\Block\LogWriter\TextfileLogWriter;
use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\SimpleMatchEntity;

class StatusCodeTest extends ServerBasedTest
{
    public function testNonProcessableBaseUrl(): void
    {
        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl(ServerBasedTest::getHostUrl() . '/non-existent')
            ->addLogWriter(new TextfileLogWriter())
            ->addObjectDefinition('test', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('span.match'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $log = file(__DIR__ . '/output/test/crawler.log');
        $this->assertRegExp('#Skipped due to unprocessable response code#', $log[1]);
    }
}
