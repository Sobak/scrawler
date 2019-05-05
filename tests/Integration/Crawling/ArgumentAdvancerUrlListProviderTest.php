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
 * @covers \Sobak\Scrawler\Block\UrlListProvider\AbstractUrlListProvider
 * @covers \Sobak\Scrawler\Block\UrlListProvider\ArgumentAdvancerUrlListProvider
 * @covers \Sobak\Scrawler\Client\ClientFactory
 * @covers \Sobak\Scrawler\Configuration\Configuration
 * @covers \Sobak\Scrawler\Configuration\ConfigurationChecker
 * @covers \Sobak\Scrawler\Configuration\ObjectConfiguration
 * @covers \Sobak\Scrawler\Scrawler
 * @covers \Sobak\Scrawler\Support\Utils
 */
class ArgumentAdvancerUrlListProviderTest extends IntegrationTest
{
    public function testWithStopArgument(): void
    {
        $host = IntegrationTest::getHostUrl();

        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl($host)
            ->addUrlListProvider(new ArgumentAdvancerUrlListProvider("{$host}/page-%u.html", 1, 1, 4))
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

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Second message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[2]['match']);
        $this->assertEquals('Fourth message', InMemoryResultWriter::$results[3]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[4]['match']);
    }

    public function testWithoutStopArgument(): void
    {
        $host = IntegrationTest::getHostUrl();

        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl($host)
            ->addUrlListProvider(new ArgumentAdvancerUrlListProvider("{$host}/page-%u.html", 1, 1))
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

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Second message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[2]['match']);
        $this->assertEquals('Fourth message', InMemoryResultWriter::$results[3]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[4]['match']);
    }

    public function testWithRelativeUrl(): void
    {
        $host = IntegrationTest::getHostUrl();

        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl($host)
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

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Second message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[2]['match']);
        $this->assertEquals('Fourth message', InMemoryResultWriter::$results[3]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[4]['match']);
    }

    public function testWithNonDefaultStep(): void
    {
        $host = IntegrationTest::getHostUrl();

        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl($host)
            ->addUrlListProvider(new ArgumentAdvancerUrlListProvider("{$host}/page-%u.html", 2, 2, 4))
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

        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[2]['match']);
    }
}
