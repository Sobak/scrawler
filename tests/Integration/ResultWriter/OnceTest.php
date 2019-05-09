<?php

namespace Tests\Integration\ResultWriter;

use Sobak\Scrawler\Block\Matcher\CssSelectorHtmlMatcher;
use Sobak\Scrawler\Block\Matcher\CssSelectorListMatcher;
use Sobak\Scrawler\Block\UrlListProvider\ArrayUrlListProvider;
use Sobak\Scrawler\Configuration\Configuration;
use Sobak\Scrawler\Configuration\ObjectConfiguration;
use Sobak\Scrawler\Scrawler;
use Tests\Integration\IntegrationTest;
use Tests\Utils\InMemoryResultWriter;
use Tests\Utils\SimpleMatchEntity;

/**
 * @covers \Sobak\Scrawler\Configuration\ObjectConfiguration
 * @covers \Sobak\Scrawler\Scrawler
 */
class OnceTest extends IntegrationTest
{
    public function testOnceModifier(): void
    {
        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->addUrlListProvider(new ArrayUrlListProvider(['page.html'])) // Note: index.html comes from base URL
            ->addObjectDefinition('title', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('h1#title'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                    ->once()
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertCount(1, InMemoryResultWriter::$results);
        $this->assertEquals('Page title', InMemoryResultWriter::$results[0]['match']);
    }

    public function testWithoutOnceModifier(): void
    {
        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->addUrlListProvider(new ArrayUrlListProvider(['page.html'])) // Note: index.html comes from base URL
            ->addObjectDefinition('title', new CssSelectorListMatcher('body'), function (ObjectConfiguration $object) {
                $object
                    ->addFieldDefinition('match', new CssSelectorHtmlMatcher('h1#title'))
                    ->addEntityMapping(SimpleMatchEntity::class)
                    ->addResultWriter(SimpleMatchEntity::class, new InMemoryResultWriter())
                ;
            })
        ;

        $scrawler = new Scrawler($config, __DIR__ . '/output');
        $scrawler->run();

        $this->assertCount(2, InMemoryResultWriter::$results);
        $this->assertEquals('Page title', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Page title', InMemoryResultWriter::$results[1]['match']);
    }
}
