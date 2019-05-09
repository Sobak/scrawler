<?php

declare(strict_types=1);

namespace Tests\Integration\Crawling;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\UrlListProvider\ArgumentAdvancerUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\InMemoryResultWriter;
use Tests\Utils\SimpleMatchEntity;

/**
 * @covers \Sobak\Scrawler\Configuration\Configuration
 * @covers \Sobak\Scrawler\Scrawler
 */
class MaxCrawledUrlsTest extends IntegrationTest
{
    public function testMaxCrawledUrlsOption(): void
    {
        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->setMaxCrawledUrls(3)
            ->addUrlListProvider(new ArgumentAdvancerUrlListProvider("/page-%u.html", 1, 1))
            ->addObjectDefinition('message', new CssSelectorListMatcher('div.message'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('span.content'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertCount(3, InMemoryResultWriter::$results);
        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Second message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[2]['match']);
    }
}
