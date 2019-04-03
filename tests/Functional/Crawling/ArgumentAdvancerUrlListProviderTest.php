<?php

declare(strict_types=1);

namespace Tests\Functional\Crawling;

use Sobak\Scrawler\Block\ResultWriter\InMemoryResultWriter;
use Sobak\Scrawler\Block\UrlListProvider\ArgumentAdvancerUrlListProvider;
use Sobak\Scrawler\Block\UrlListProvider\EmptyUrlListProvider;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Matcher\CssSelectorTextMatcher;
use Sobak\Scrawler\Scrawler;
use Tests\Functional\ServerBasedTest;
use Tests\Utils\BasicConfigurationProvider;
use Tests\Utils\SimpleMatchEntity;

class ArgumentAdvancerUrlListProviderTest extends ServerBasedTest
{
    public function testWithStopArgument(): void
    {
        $host = ServerBasedTest::getHostUrl();

        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl($host)
            ->removeUrlListProvider(EmptyUrlListProvider::class)
            ->addUrlListProvider(new ArgumentAdvancerUrlListProvider("{$host}/page-%u.html", 1, 1, 4))
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.message'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorTextMatcher('span.content'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Second message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[2]['match']);
        $this->assertEquals('Fourth message', InMemoryResultWriter::$results[3]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[4]['match']);
    }

    public function testWithNonDefaultStep(): void
    {
        $host = ServerBasedTest::getHostUrl();

        $config = BasicConfigurationProvider::getConfiguration()
            ->setBaseUrl($host)
            ->removeUrlListProvider(EmptyUrlListProvider::class)
            ->addUrlListProvider(new ArgumentAdvancerUrlListProvider("{$host}/page-%u.html", 2, 2, 4))
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.message'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorTextMatcher('span.content'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[2]['match']);
    }
}
