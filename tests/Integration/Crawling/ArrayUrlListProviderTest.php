<?php

declare(strict_types=1);

namespace Tests\Integration\Crawling;

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
 * @covers \Sobak\Scrawler\Block\UrlListProvider\AbstractUrlListProvider
 * @covers \Sobak\Scrawler\Block\UrlListProvider\ArrayUrlListProvider
 * @covers \Sobak\Scrawler\Configuration\Configuration
 * @covers \Sobak\Scrawler\Configuration\ConfigurationChecker
 * @covers \Sobak\Scrawler\Configuration\ObjectConfiguration
 * @covers \Sobak\Scrawler\Scrawler
 */
class ArrayUrlListProviderTest extends IntegrationTest
{
    public function testArrayUrlListProvider(): void
    {
        $urls = [
            'page-2.html',
            'page-4.html'
        ];

        $config = (new Configuration())
            ->setOperationName('test')
            ->setBaseUrl(IntegrationTest::getHostUrl())
            ->addUrlListProvider(new ArrayUrlListProvider($urls))
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

        // Note: first match comes from the base URL
        $this->assertEquals('First message', InMemoryResultWriter::$results[0]['match']);
        $this->assertEquals('Third message', InMemoryResultWriter::$results[1]['match']);
        $this->assertEquals('Fifth message', InMemoryResultWriter::$results[2]['match']);
    }
}
